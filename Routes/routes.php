<?php
/**
 * @uses App\Controllers\AuthController::login()
 * @uses App\Controllers\AuthController::verify()
 * @uses App\Controllers\AuthController::logout()
 * @uses App\Controllers\SaleController::index()
 * @uses App\Controllers\SaleController::show()
 * @uses App\Controllers\SaleController::store()
 * @uses App\Controllers\SaleController::update()
 * @uses App\Controllers\SaleController::delete()
 * @uses App\Controllers\ProductController::index()
 * @uses App\Controllers\ProductController::show()
 * @uses App\Controllers\ProductController::store()
 * @uses App\Controllers\ProductController::update()
 * @uses App\Controllers\ProductController::delete()
 * @uses App\Controllers\TypeController::index()
 * @uses App\Controllers\TypeController::show()
 * @uses App\Controllers\TypeController::store()
 * @uses App\Controllers\TypeController::update()
 * @uses App\Controllers\TypeController::delete()
 * @uses App\Controllers\UserController::index()
 * @uses App\Controllers\UserController::show()
 * @uses App\Controllers\UserController::store()
 * @uses App\Controllers\UserController::update()
 * @uses App\Controllers\UserController::delete()
 */

// The routes.php file defines the routes for the API.
// The key of the array is the HTTP method, and the value is another array of routes.
// The key of the nested array is a path, and the value is the controller and method to call.
// The controller and method are separated by an @ symbol.

$router = new Routes\Router();

$router->get("/users", "UserController", "index", "admin");
$router->get("/user", "UserController", "show");
$router->post("/user/store", "UserController", "store", "guest");
$router->put("/user/update", "UserController", "update");
$router->delete("/user/delete", "UserController", "delete");

$router->get("/sales", "SaleController", "index", "admin");
$router->get("/sale", "SaleController", "show");
$router->post("/sale/store", "SaleController", "store");
$router->put("/sale/update", "SaleController", "update", "admin");
$router->delete("/sale/delete", "SaleController", "delete", "admin");

$router->get("/types", "TypeController", "index", "guest");
$router->get("/type", "TypeController", "show", "guest");
$router->post("/type/store", "TypeController", "store", "admin");
$router->put("/type/update", "TypeController", "update", "admin");
$router->delete("/type/delete", "TypeController", "delete", "admin");

$router->get("/products", "ProductController", "index", "guest");
$router->get("/product", "ProductController", "show", "guest");
$router->post("/product/store", "ProductController", "store", "admin");
$router->put("/product/update", "ProductController", "update", "admin");
$router->delete("/product/delete", "ProductController", "delete", "admin");

$router->post("/login", "AuthController", "login", "guest");
$router->post("/verify", "AuthController", "verify");
$router->post("/logout", "AuthController", "logout");
