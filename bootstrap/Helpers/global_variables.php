<?php
define('APP_ROOT',dirname(dirname(__DIR__)));
define('PUBLIC_ROOT',env('APP_URL'));
define('BLADE_VIEW_PATH',APP_ROOT."/resources/views");
define('BLADE_CACHE_PATH',APP_ROOT."/storage/cache/views");
