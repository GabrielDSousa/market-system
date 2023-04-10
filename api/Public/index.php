<?php

const BASE_PATH = __DIR__ . "/../";

require BASE_PATH . "App/helpers.php";

spl_autoload_register(function ($class) {
    $class = str_replace("\\", DIRECTORY_SEPARATOR, $class);
    $file = base_path($class);
    if (file_exists($file)) {
        require $file;
    }
});

$router = new Routes\Router();
require base_path("Routes/routes");

// Get the result of the controller method call and echo it or show an error.
try {
    echo $router->routeToController();
} catch (Exception $e) {
    if (gettype($e->getCode()) !== "integer") {
        echo App\Requests\Request::abort($e->getMessage(), 500);
    } else {
        echo App\Requests\Request::abort($e->getMessage(), $e->getCode());
    }
}
