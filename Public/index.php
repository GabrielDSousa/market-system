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
    $result = $router->routeToController();
    echo $result;
} catch (Exception $e) {
    // If an exception is thrown, return a 500 response.
    echo App\Requests\ApiResponse::abort($e->getCode(), $e->getMessage());
}
