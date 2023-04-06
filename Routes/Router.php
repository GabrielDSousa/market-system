<?php

namespace Routes;

use App\Requests\ApiResponse;
use Exception;

// This code is a router for a REST API. It parses the URL and HTTP method to determine which controller
// and which method to call, and passes the parameters to the controller method as arguments.
// The routeToController() function returns the result of the controller method call, which is the
// response body of the API request.
/**
 * Summary of Router
 */
class Router
{
    private array $routes;
    private array|bool|int|string|null $path;
    private ?string $query;
    private string $httpMethod;
    private array $parameters;
    public function __construct()
    {
        // Get the path from the URL. The path is everything after the domain name up to the query string.
        $this->path = parse_url($_SERVER["REQUEST_URI"])["path"];
        // Get the query string from the URL. This is everything after the ? symbol.
        $this->query = parse_url($_SERVER["REQUEST_URI"])["query"] ?? null;
        // Get the HTTP method from the request.
        $this->httpMethod = $_SERVER["REQUEST_METHOD"];
        // Get the parameters from the request body or query string.
        $this->parameters = $this->getParameters();
        // Set the Content-Type header to application/json.
        header("Content-Type: application/json; charset=utf-8");
    }

    /**
     * This function gets the parameters from the request body or query string.
     * 
     * @return array
     */
    private function getParameters(): array
    {
        // If the HTTP method is GET and the query string is not null, get the parameters from the query string.
        // Otherwise, get the parameters from the request body.
        if ($this->httpMethod === "GET" && $this->query !== null) {
            // Get the parameters from the query string.
            parse_str($this->query, $parameters);
        } else {
            // Get the parameters from the request body.
            $parameters = json_decode(file_get_contents("php://input"), true);
        }

        return $parameters ?? [];
    }

    /**
     * This function takes the path and routes, and returns the result of the controller method call.
     * 
     * @return array|void
     */
    public function routeToController()
    {
        // If the path is not in the routes array, return a 404 response.
        if (!array_key_exists($this->path, $this->routes[$this->httpMethod])) {
            ApiResponse::abort(ApiResponse::NOT_FOUND);
        }

        // Get the controller and method from the routes array.
        $action = explode("@", $this->routes[$this->httpMethod][$this->path]);
        // The controller is the first element in the array.
        $controller = $action[0];
        // The method is the second element in the array.
        $method = $action[1];

        $controller = "App\\Controllers\\" . $controller;

        if (class_exists($controller)) {
            $controller_instance = new $controller;
            if (method_exists($controller_instance, $method)) {
                try {
                    return call_user_func_array([$controller_instance, $method], $this->parameters);
                } catch (Exception $e) {
                    throw new Exception($e->getMessage(), $e->getCode());
                }
            }
        }
    }

    private function add(string $verb, string $path, string $controller): void
    {
        $this->routes[strtoupper($verb)][$path] = $controller;
    }

    /**
     * This function adds a GET route to the routes array.
     * @param string $path
     * @param string $controller
     * @return void
     */
    public function get(string $path, string $controller): void
    {
        $this->add("GET", $path, $controller);
    }

    /**
     * This function adds a POST route to the routes array.
     * @param string $path
     * @param string $controller
     * @return void
     */
    public function post(string $path, string $controller): void
    {
        $this->add("POST", $path, $controller);
    }

    /**
     * This function adds a PUT route to the routes array.
     * @param string $path
     * @param string $controller
     * @return void
     */
    public function put(string $path, string $controller): void
    {
        $this->add("PUT", $path, $controller);
    }

    /**
     * This function adds a DELETE route to the routes array.
     * @param string $path
     * @param string $controller
     * @return void
     */
    public function delete(string $path, string $controller): void
    {
        $this->add("DELETE", $path, $controller);
    }

    /**
     * This function returns the routes array.
     * 
     * @return array
     */
    public function getRoutes(): array
    {
        return $this->routes;
    }
}
