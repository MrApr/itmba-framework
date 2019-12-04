<?php


namespace Services;


use Illuminate\Http\Request;
use Services\Cores\Exceptions\ControllerNotFound;
use Services\Cores\Exceptions\MethodNotFound;
use Services\Cores\Exceptions\RouteMethodNotFound;
use Services\Cores\Exceptions\RouteNotFound;

class Router
{
    /**
     * Defining Routes Container
     * @var array
     */
    protected $routes = [];

    /**
     * Defining temp Request Method
     * @var
     */
    protected $method;

    /**
     * Defining Temp Middleware
     * @var
     */
    protected $middleware;

    /**Defining Temp Route Prefixes
     * @var
     */
    protected $prefix;

    /**
     * Called When a method doesnt exist to predefine GET and Post Methods
     * @param $name
     * @param $arguments
     * @return $this
     * @throws RouteMethodNotFound
     */
    public function __call($name, $arguments)
    {
        $name = strtolower($name);
        if($name == "post" || $name == "get")
        {
            $this->method = $name;
            $this->addRoute(trim($arguments[0],'/'),$arguments[1]);
            return $this;
        }
        else
        {
            throw new RouteMethodNotFound("Method not found");
        }
    }

    /**
     * Add routes to Route Container
     * @param string $url
     * @param string $action
     */
    protected function addRoute(string $url ,string $action)
    {
        $arguments = [];

        $arguments['method'] = $this->method;
        $arguments['url'] = $url;
        $arguments['action'] = $action;
        if(!empty($this->middleware))
        {
            $arguments['middleware'] = $this->middleware;
        }
        if(!empty($this->prefix))
        {
            $arguments['prefix'] = $this->prefix;
        }

        $this->routes[] = $arguments;
    }

    /**
     * Cleaning Temp Properties after a task got done
     */
    protected function cleaningProperties()
    {
        unset($this->method);
        unset($this->middleware);
        unset($this->prefix);
    }

    /**
     * Setting name For Routes
     * @param string $route_name
     */
    public function name(string $route_name)
    {
        $routes = $this->routes;
        end($routes);
        $route = $routes[key($routes)];
        $route["name"] = $route_name;
        unset($routes[key($routes)]);
        $this->routes[] = $route;
    }

    /**
     * Setting middleware to temp middleware
     * @param string $middelware_name
     * @return $this
     */
    public function middleware(string $middelware_name)
    {
        $this->middleware = $middelware_name;
        return $this;
    }

    /**
     * Setting Prefix For Routes
     * @param string $prefix
     * @return $this
     */
    public function prefix(string $prefix)
    {
        $this->prefix = $prefix;
        return $this;
    }

    /**
     * Async Functions to set group of Routes
     * @param \Closure $group
     * @return $this
     */
    public function group(\Closure $group)
    {
        call_user_func($group);
        $this->cleaningProperties();
        return $this;
    }

    /**
     * Check if a route exists by name
     * @param string $route_name
     * @return mixed
     */
    public function has(string $route_name)
    {
        foreach ($this->routes as $route)
        {
            if(isset($route['name']) && $route['name'] == $route_name)
            {
                return $route;
            }
        }
    }

    /**
     * Make Route url by passed route array
     * @param array $route
     * @return string
     */
    public function makeRoute(array $route)
    {
        $url = "/";

        if(isset($route['prefix']) && !empty($route['prefix']))
        {
            $url .= $route['prefix']."/";
        }

        $url .= $route['url'];

        return $url;
    }

    /**
     * Executes routes by user Request to call controller and it's method
     * @throws ControllerNotFound
     * @throws MethodNotFound
     * @throws RouteNotFound
     */
    public function execute(Request $request)
    {
        $url = rtrim(strtok($_SERVER['REQUEST_URI'],'?'),'/');
        $route = $this->checkRoutesAreEqual($url);
        if(empty($route))
        {
            throw new RouteNotFound("Requested Route Doesnt Exist");
        }
        $route_url = $this->makeRoute($route);
        $params = $this->extractParams($url,$route_url);
        $action = explode('@',$route['action']);
        $controller = "App\\Controllers\\".str_replace("/",'\\',$action[0]);
        $method = $action[1];

        if(!class_exists($controller))
        {
            throw new ControllerNotFound('Controller Not Found');
        }

        $controller = new $controller();
        if(!method_exists($controller,$method))
        {
            throw new MethodNotFound('Controller Not Found');
        }
        $has_request_param = $this->checkIfMethodHasTypeOfArgs($controller,$method,"Illuminate\Http\Request");
        if(isset($has_request_param))
        {
//            $params[] = $request;
            array_splice($params,$has_request_param,0,[$request]);
        }
        call_user_func_array([$controller,$method],$params);
    }


    public function checkIfMethodHasTypeOfArgs(object $class, string $method, $needed)
    {
        $params_checker = new \ReflectionMethod($class,$method);
        $params = $params_checker->getParameters();
        $index = 0;
        foreach ($params as $param)
        {
            if($param->getType() == $needed)
            {
                 return $index;
            }
            $index++;
        }
        return false;
    }

    /**
     * Check if user requested route exists in registered routes
     * @param string $requested_url
     * @return mixed
     */
    public function checkRoutesAreEqual(string $requested_url)
    {
        $routes = $this->routes;

        foreach ($routes as $route)
        {
            $url = $this->makeRoute($route);
            $pattern = preg_replace('/{(.*?)}/','*',$url);
            if(fnmatch($pattern,$requested_url))
            {
                return $route;
            }
        }
    }

    /**
     * Extract passed params from route
     * @param string $url
     * @param string $pattern
     * @return mixed
     */
    public function extractParams(string $url, string $pattern)
    {
        $pattern = preg_replace('/{(.*?)}/','*',$pattern);
        $pattern = str_replace('/','\/',$pattern);
        $pattern = str_replace('*','(.*?)',$pattern);
        preg_match_all("/".$pattern."/",$url,$matches);
        return $matches[1];
    }
}