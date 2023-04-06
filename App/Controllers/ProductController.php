<?php

namespace App\Controllers;

use App\Requests\Validator;
use App\Requests\ApiResponse;
use Exception;
use PDOException;
use Model\Type;
use Model\Product;

/**
 * Summary of ProductController
 */
class ProductController
{
    private Type $type;

    private Product $product;

    public function __construct()
    {
        $this->product = new Product();
    }

    /**
     * This function fetches all products
     * 
     * @return string A JSON array of objects string encoded with all products
     */
    public function index(): string
    {
        // Get the all products from the database or fail on error.
        try {
            $products = $this->product->all();
        } catch (Exception $e) {
            return ApiResponse::abort($e->getCode(), $e->getMessage());
        }

        // Return the products as a JSON array of objects, as a success.
        return ApiResponse::success($products);
    }

    /**
     * Find a product by id
     *
     * @param int $id
     * @return string A JSON object string encoded with an product
     */
    public function show(int $id): string
    {
        // Get the product from the database or fail if not found or on error.
        try {
            $product = $this->product->get($id);
        } catch (Exception $e) {
            return ApiResponse::abort($e->getCode(), $e->getMessage());
        }

        // Return the product as a JSON object, as a success.
        return ApiResponse::success($product);
    }

    /**
     * This function creates a new product or update an existing product
     * 
     * @param string $name
     * @param int $value
     * @param int $type_id
     * @param null|string $description
     * @param int|null $id
     * @return string
     */
    public function store(string $name, int $value, int $type_id, ?string $description = null, ?int $id = null): string
    {
        //Validate the data
        $validator = new Validator($this->product, [
            "name" => $name,
            "value" => $value,
            "type_id" => $type_id,
            "description" => $description
        ]);

        //Check if the validation fails
        if ($validator->fails()) {
            //Abort the request with the error message
            return ApiResponse::abort(ApiResponse::BAD_REQUEST, $validator->errors());
        }

        try {
            //Get the validated data
            $safe = $validator->validated();

            $action = "created";

            $product = new Product();
            $product->setName($safe["name"]);
            $product->setDescription($safe["description"]);
            $product->setValue($safe["value"]);
            $product->setTypeId($safe["type_id"]);

            if (!empty($id)) {
                $product->setId($id);
                $action = "updated";
            }

            $message = "Product {$action} successfully.";

            //Save or update the product in the database, 
            //sending the data as an array of parameters for the prepared statement
            //and id if is an update
            return ApiResponse::success($this->product->saveOrUpdate([
                ":name" => $product->getName(),
                ":description" => $product->getDescription(),
                ":value" => $product->getValue(),
                ":type_id" => $product->getTypeId()
            ], $id) . " " . $message);
        } catch (Exception $e) {
            //Abort the request with the error message
            return ApiResponse::abort($e->getCode(), $e->getMessage());
        } catch (PDOException $e) {
            //Abort the request with the error message
            return ApiResponse::abort($e->getCode(), $e->getMessage());
        }
    }


    /**
     * Update a product
     *
     * @param int $id
     * @param null|string $name
     * @param null|int $value
     * @param null|int $type_id
     * @param null|string $description
     * @return string
     */
    public function update(int $id = null, ?string $name = null, ?int $value = null, ?int $type_id = null, ?string $description = null): string
    {

        // Get the product's data from the database
        $parameters = $this->product->get($id);

        // Set the Product object's properties
        $name = empty($name) ? $parameters["name"] : $name;
        $value = empty($value) ? $parameters["value"] : $value;
        $type_id = empty($type_id) ? $parameters["type_id"] : $type_id;
        $description = empty($description) ? $parameters["description"] : $description;
        $this->product->setId($id);

        // Update the product in the database
        return $this->store($name, $value, $type_id, $description, $id);
    }

    /**
     * Delete a product
     * 
     * @param int $id
     * @return string
     */
    public function delete(int $id): string
    {
        // Delete the product from the database
        try {
            //Look if the product exists
            $this->product->get($id);
            $this->product->delete($id);
        } catch (Exception $e) {
            return ApiResponse::abort($e->getCode(), $e->getMessage());
        }

        // Return a success message
        return ApiResponse::success("Product deleted");
    }
}
