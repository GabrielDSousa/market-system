<?php

// The routes.php file defines the routes for the API.
// The key of the array is the HTTP method, and the value is another array of routes.
// The key of the nested array is a path, and the value is the controller and method to call.
// The controller and method are separated by an @ symbol.

$router->get("/users", "UserController@index");
$router->get("/user", "UserController@show");
$router->post("/user/store", "UserController@store");
$router->put("/user/update", "UserController@update");
$router->delete("/user/delete", "UserController@delete");

$router->get("/types", "TypeController@index");
$router->get("/type", "TypeController@show");
$router->post("/type/store", "TypeController@store");
$router->put("/type/update", "TypeController@update");
$router->delete("/type/delete", "TypeController@delete");
