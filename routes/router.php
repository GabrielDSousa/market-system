<?php

// This code is a router for a REST API. It parses the URL and HTTP method to determine which controller
// and which method to call, and passes the parameters to the controller method as arguments.
// The routeToController() function returns the result of the controller method call, which is the
// response body of the API request.

// The routes.php file defines the routes for the API.
$routes = require "routes/routes.php";

// Get the path from the URL. The path is everything after the domain name up to the query string.
$path = parse_url($_SERVER["REQUEST_URI"])["path"];
// Get the query string from the URL. This is everything after the ? symbol.
$query = parse_url($_SERVER["REQUEST_URI"])["query"] ?? null;
// Get the HTTP method from the request.
$httpMethod = $_SERVER["REQUEST_METHOD"];

// Set the Content-Type header to application/json.
header("Content-Type: application/json; charset=utf-8");

/**
 * This function takes a query string and returns an array of parameters.
 * 
 * @param string|null $query
 * @return array
 */
function parametersFromQuery(?string $query = null): array
{
    // If the query string is null, return an empty array.
    if ($query === null) {
        return [];
    }

    // Initialize an empty array to hold the parameters.
    $parameters = [];
    // Split the query string by the & symbol to get an array of parameters.
    $query = explode("&", $query);

    // Loop over the array of parameters.
    foreach ($query as $parameter) {
        // Split the parameter by the = symbol to get the parameter name and value.
        $parameter = explode("=", $parameter);
        // Add the parameter to the parameters array.
        $parameters[$parameter[0]] = $parameter[1];
    }

    // Return the parameters array.
    return $parameters;
}

/**
 * This function takes the path and routes, and returns the result of the controller method call.
 * 
 * @param string $uri
 * @param array $routes
 * @param string $httpMethod
 * @param string|null $query
 * @return array|void
 */
function routeToController($uri, $routes, $httpMethod, $query = null)
{
    // If the path is not in the routes array, return a 404 response.
    if (!array_key_exists($uri, $routes[$httpMethod])) {
        ApiResponse::abort(ApiResponse::NOT_FOUND);
    }

    // Get the controller and method from the routes array.
    $action = explode("@", $routes[$httpMethod][$uri]);
    // The controller is the first element in the array.
    $controller = $action[0];
    // The method is the second element in the array.
    $method = $action[1];

    $parameters = [];
    // If the HTTP method is GET and the query string is not null, get the parameters from the query string.
    // Otherwise, get the parameters from the request body.
    if ($httpMethod === "GET" && $query !== null) {
        // Get the parameters from the query string.
        $parameters = parametersFromQuery($query);
    } else {
        $content = file_get_contents("php://input");
        // If the request body is not empty, add it to the parameters array.
        if (!empty($content)) {
            $parameters = json_decode(file_get_contents("php://input"), true);
        }
    }


    if (class_exists($controller)) {
        $controller_instance = new $controller;
        if (method_exists($controller_instance, $method)) {
            try {
                return call_user_func_array([$controller_instance, $method], $parameters);
            } catch (Exception $e) {
                throw new Exception($e->getMessage(), $e->getCode());
            }
        }
    }
}

// Get the result of the controller method call and echo it or show an error.
try {
    $result = routeToController($path, $routes, $httpMethod, $query);
    echo $result;
} catch (Exception $e) {
    // If an exception is thrown, return a 500 response.
    echo ApiResponse::abort($e->getCode(), $e->getMessage());
}
