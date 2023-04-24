<?php

namespace App\Requests;

abstract class Request
{
    public const NOT_FOUND = 404;
    public const INTERNAL_SERVER_ERROR = 500;
    public const OK = 200;
    public const CREATED = 201;
    public const NO_CONTENT = 204;
    public const BAD_REQUEST = 400;
    public const UNAUTHORIZED = 401;
    public const FORBIDDEN = 403;
    public const UNPROCESSABLE_ENTITY = 422;
    public const MESSAGES = [
        404 => "Not found",
        500 => "Internal server error",
        200 => "OK",
        201 => "Created",
        204 => "No content",
        400 => "Bad request",
        401 => "Unauthorized",
        403 => "Forbidden",
        422 => "Unprocessable entity",
        429 => "Too many requests",
    ];

    /**
     * This function gets the Authorization header.
     *
     * @return string|null
     */
    private static function getAuthorizationHeader(): ?string
    {
        $headers = null;
        //Check whether the Authorization header is set.
        if (isset($_SERVER['Authorization'])) {
            $headers = trim($_SERVER["Authorization"]);
        } elseif (isset($_SERVER['HTTP_AUTHORIZATION'])) { //Nginx or fast CGI
            $headers = trim($_SERVER["HTTP_AUTHORIZATION"]);
        } elseif (function_exists('apache_request_headers')) {
            //If it isn't, try to get it from the other sources.
            $requestHeaders = apache_request_headers();
            // Server-side fix for bug in old Android versions (a nice side effect of this fix means we don't care about capitalization for Authorization)
            $requestHeaders = array_combine(
                array_map('ucwords', array_keys($requestHeaders)),
                array_values($requestHeaders)
            );
            if (isset($requestHeaders['Authorization'])) {
                $headers = trim($requestHeaders['Authorization']);
            }
        }
        return $headers;
    }

    /**
     * This function gets the Bearer token from the Authorization header.
     *
     * @return null|string
     */
    public static function getBearerToken(): ?string
    {
        $headers = self::getAuthorizationHeader();

        // HEADER: Get the access token from the header
        if (!empty($headers)) {
            if (preg_match('/Bearer\s(\S+)/', $headers, $matches)) {
                return $matches[1];
            }
        }
        return null;
    }

    /**
     * Returns a JSON error response with the specified message and status code.
     *
     * @param string $message The message to be overwritten in the response
     * @param int $code The status code of the response
     * @return string The JSON response
     */

    public static function abort(mixed $message = "", int $code = self::INTERNAL_SERVER_ERROR): string
    {
        // if a custom message was passed, use it
        if (empty($message)) {
            $message = self::MESSAGES[$code];
        }

        // return the json response
        return self::json($message, $code);
    }

    /**
     * Returns a JSON success response with the specified data and status code of success.
     *
     * @param mixed $data The data to be displayed in the response
     * @return string The JSON response
     */
    public static function success(mixed $data, int $code = self::OK): string
    {
        return self::json($data, $code);
    }

    /**
     * Returns a JSON-encoded response
     *
     * @param mixed $data The data to be displayed in the response
     * @return string The JSON response
     */
    protected static function json(mixed $data, int $code): string
    {
        // Set the HTTP response code
        http_response_code($code);

        // Return the JSON-encoded data
        return json_encode($data);
    }
}
