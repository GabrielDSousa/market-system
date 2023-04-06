<?php

namespace App\Requests;

class ApiResponse
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
    public const TOO_MANY_REQUESTS = 429;
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
     * Returns a JSON error response with the specified message and status code.
     *
     * @param int $code The status code of the response
     * @param mixed $customMsg The message to be overwritten in the response
     * @return string The JSON response
     */

    public static function abort(int $code, $customMsg = null): string
    {
        // get the message from the ApiResponse class
        $message = ApiResponse::MESSAGES[$code] ?? "Unknown error";

        // if a custom message was passed, use it
        if ($customMsg) {
            $message = $customMsg;
        }

        // return the json response
        return self::json($message, "error", $code);
    }

    /**
     * Returns a JSON success response with the specified data and status code of success.
     *
     * @param mixed $data The data to be displayed in the response
     * @return string The JSON response
     */
    public static function success($data): string
    {
        return self::json($data);
    }

    /**
     * Returns a JSON-encoded response
     *
     * @param string $data The data to be displayed in the response
     * @return string The JSON response
     */
    private static function json($data, $status = "success",  $code = ApiResponse::OK): string
    {
        // Set the HTTP response code
        http_response_code($code);

        // Return the JSON-encoded data
        return json_encode([
            "status" => $status,
            "data" => $data,
            "code" => $code
        ]);
    }
}
