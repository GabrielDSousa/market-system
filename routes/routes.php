<?php

// The routes.php file defines the routes for the API.
// The key of the array is the HTTP method, and the value is another array of routes.
// The key of the nested array is a path, and the value is the controller and method to call.
// The controller and method are separated by an @ symbol.

// The UserController.php file defines the UserController class.
require "app/controllers/UserController.php";


return [
    "GET" => [
        "/users" => "UserController@index",
        "/user" => "UserController@show"
    ],
    "POST" => [
        "/user/store" => "UserController@store"
    ],
    "PUT" => [
        "/user/update" => "UserController@update"
    ],
    "DELETE" => [
        "/user/delete" => "UserController@delete"
    ]
];
