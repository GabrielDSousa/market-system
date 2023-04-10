<?php

namespace Routes;

use App\Middleware\Middleware;
use App\Requests\Request;
use Exception;
use TypeError;

// This code is a router for a REST API. It parses the URL and HTTP method to determine which controller
// and which method to call, and passes the parameters to the controller method as arguments.
// The routeToController() function returns the result of the controller method call, which is the
// response body of the API request.

/**
 * Summary of Router
 */
class Router extends Request
{
    private array $routes;
    private array|bool|int|string|null $path;
    private ?string $query;
    private string $httpMethod;
    private array $parameters;
    private array $server;

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
        // Get the routes verbs to be populated in the routes array.
        $this->routes = $this->getRoutes();
    }

    /**
     * This function gets the parameters from the request body or query string.
     *
     * @return array
     */
    private function getParameters(): array
    {
        $parameters = [];

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
     * @throws Exception
     */
    public function routeToController()
    {
        header("Content-Type: application/json");
        header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
        header("Access-Control-Allow-Origin: *");
        header(
            "Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With"
        );
        header("Access-Control-Allow-Credentials: true");
        header("allow: GET, POST, PUT, DELETE, OPTIONS");

        if ($this->httpMethod == "OPTIONS") {
            http_response_code(self::OK);
            exit();
        }


        // If the path is not in the routes array, return a 404 response.
        if (!array_key_exists($this->path, $this->routes[$this->httpMethod])) {
            throw new Exception("Route not found", self::NOT_FOUND);
        }

        $this->haveAccess();

        // The controller is the first element in the array.
        $controller = "App\\Controllers\\" . $this->routes[$this->httpMethod][$this->path]["controller"];

        // The method is the second element in the array.
        $method = $this->routes[$this->httpMethod][$this->path]["method"];

        if (class_exists($controller)) {
            $controller_instance = new $controller;
            if (method_exists($controller_instance, $method)) {
                return call_user_func_array([$controller_instance, $method], $this->parameters);
            } else {
                throw new Exception("Method not found", self::NOT_FOUND);
            }
        } else {
            throw new Exception("Controller not found", self::NOT_FOUND);
        }
    }

    /**
     * @throws Exception
     */
    private function haveAccess(): void
    {
        $middleware = $this->routes[$this->httpMethod][$this->path]["middleware"];
        $bearer = self::getBearerToken();

        $middleware = Middleware::MAP[$middleware];

        if (!class_exists($middleware)) {
            throw new Exception("Middleware class does not exist", self::INTERNAL_SERVER_ERROR);
        }

        try {
            (new $middleware)->handle($bearer);
        } catch (TypeError $e) {
            if (str_contains($e->getMessage(), '($bearer) must be of type string, null given')) {
                throw new Exception("Token not found", self::UNAUTHORIZED);
            }
            throw new Exception($e->getMessage(), self::INTERNAL_SERVER_ERROR);
        }
    }

    private function add(
        string $verb,
        string $path,
        string $controller,
        string $method,
        string $middleware = "auth"
    ): self {
        $this->routes[$verb][$path] = [
            "controller" => $controller,
            "method" => $method,
            "middleware" => $middleware
        ];

        return $this;
    }

    /**
     * This function adds a GET route to the routes array.
     * @param string $path
     * @param string $controller
     * @param string $method
     * @param string $middleware
     * @return Router
     */
    public function get(string $path, string $controller, string $method, string $middleware = "auth"): self
    {
        return $this->add("GET", $path, $controller, $method, $middleware);
    }

    /**
     * This function adds a POST route to the routes array.
     * @param string $path
     * @param string $controller
     * @param string $method
     * @param string $middleware
     * @return self
     */
    public function post(string $path, string $controller, string $method, string $middleware = "auth"): self
    {
        return $this->add("POST", $path, $controller, $method, $middleware);
    }

    /**
     * This function adds a PUT route to the routes array.
     * @param string $path
     * @param string $controller
     * @param string $method
     * @param string $middleware
     * @return self
     */
    public function put(string $path, string $controller, string $method, string $middleware = "auth"): self
    {
        return $this->add("PUT", $path, $controller, $method, $middleware);
    }

    /**
     * This function adds a DELETE route to the routes array.
     * @param string $path
     * @param string $controller
     * @param string $method
     * @param string $middleware
     * @return self
     */
    public function delete(string $path, string $controller, string $method, string $middleware = "auth"): self
    {
        return $this->add("DELETE", $path, $controller, $method, $middleware);
    }

    /**
     * This function returns the routes array.
     *
     * @return array
     */
    public function getRoutes(): array
    {
        return [
            "GET" => [],
            "POST" => [],
            "PUT" => [],
            "DELETE" => []
        ];
    }
}
