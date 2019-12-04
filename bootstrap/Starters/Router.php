<?php


namespace Services;


use Services\Cores\Exceptions\RouteMethodNotFound;

class Router
{
    protected $routes = [];

    protected $method;

    protected $middleware;

    protected $prefix;

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

    protected function cleaningProperties()
    {
        unset($this->method);
        unset($this->middleware);
        unset($this->prefix);
    }

    public function name(string $route_name)
    {
        $routes = $this->routes;
        end($routes);
        $route = $routes[key($routes)];
        $route["name"] = $route_name;
        unset($routes[key($routes)]);
        $this->routes[] = $route;
    }

    public function middleware(string $middelware_name)
    {
        $this->middleware = $middelware_name;
        return $this;
    }

    public function prefix(string $prefix)
    {
        $this->prefix = $prefix;
        return $this;
    }

    public function group(\Closure $group)
    {
        call_user_func($group);
        $this->cleaningProperties();
        return $this;
    }

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
}