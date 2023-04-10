<?php

use JetBrains\PhpStorm\NoReturn;

/**
 * This function prints out a variable and then stops the script.
 * If the variable is an array, it uses print_r, otherwise it uses var_dump.
 *
 * @param mixed $var
 * @return void
 */
#[NoReturn] function dd(mixed $var): void
{
    // Check if the variable is an array
    if (is_array($var)) {
        // Use print_r to print the variable
        echo json_encode($var);
        die();
    }
    // Use var_dump to print the variable
    var_dump($var);
    die();
}

/**
 * This function returns the path to a file.
 *
 * @param string $class
 * @return string
 */
function base_path(string $class): string
{
    return __DIR__ . "/../" . $class . ".php";
}

/**
 * This function returns the config array.
 * @param string|null $key
 * @return array
 */
function config(?string $key = null): array
{
    $config = require base_path("Config/" . $key);

    if ($key === null) {
        return $config;
    }

    return $config[$key];
}

/**
 * This function encodes a string to base64url.
 *
 * @param string $data
 * @return string
 */
function base64url_encode(string $data): string
{
    return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
}
