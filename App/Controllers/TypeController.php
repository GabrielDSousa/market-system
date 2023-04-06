<?php

namespace App\Controllers;

use App\Requests\Validator;
use App\Requests\ApiResponse;
use Exception;
use PDOException;
use Model\Type;

/**
 * Summary of TypeController
 */
class TypeController
{
    private Type $type;

    public function __construct()
    {
        $this->type = new Type();
    }

    /**
     * This function fetches all types
     * 
     * @return string A JSON array of objects string encoded with all types
     */
    public function index(): string
    {
        // Get the all types from the database or fail on error.
        try {
            $types = $this->type->all();
        } catch (Exception $e) {
            return ApiResponse::abort($e->getCode(), $e->getMessage());
        }

        // Return the types as a JSON array of objects, as a success.
        return ApiResponse::success($types);
    }

    /**
     * Find a type by id
     *
     * @param int $id
     * @return string A JSON object string encoded with an type
     */
    public function show(int $id): string
    {
        // Get the type from the database or fail if not found or on error.
        try {
            $type = $this->type->get($id);
        } catch (Exception $e) {
            return ApiResponse::abort($e->getCode(), $e->getMessage());
        }

        // Return the type as a JSON object, as a success.
        return ApiResponse::success($type);
    }

    /**
     * This function creates a new type or update an existing type
     * 
     * @param string $name
     * @param int $tax
     * @return string
     */
    public function store(string $name, int $tax, int $id = null): string
    {
        //Validate the data
        $validator = new Validator($this->type, [
            "name" => $name,
            "tax" => $tax
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

            $type = new Type();
            $type->setName($safe["name"]);
            $type->setTax($safe["tax"]);
            if (!empty($id)) {
                $type->setId($id);
                $action = "updated";
            }

            $message = "Type {$action} successfully.";

            //Save or update the type in the database, 
            //sending the data as an array of parameters for the prepared statement
            //and id if is an update
            return ApiResponse::success($this->type->saveOrUpdate([
                ":name" => $type->getName(),
                ":tax" => $type->getTax()
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
     * Update a type
     *
     * @param int $id
     * @param string $name
     * @param int $tax
     * @return string
     */
    public function update(int $id, string $name = null, int $tax = null): string
    {

        // Get the type's data from the database
        $parameters = $this->type->get($id);

        // Set the Type object's properties
        $name = empty($name) ? $parameters["name"] : $name;
        $tax = empty($tax) ? $parameters["tax"] : $tax;
        $this->type->setId($id);

        // Update the type in the database
        return $this->store($name, $tax, $id);
    }

    /**
     * Delete a type
     * 
     * @param int $id
     * @return string
     */
    public function delete(int $id): string
    {
        // Delete the type from the database
        try {
            //Look if the type exists
            $this->type->get($id);
            $this->type->delete($id);
        } catch (Exception $e) {
            return ApiResponse::abort($e->getCode(), $e->getMessage());
        }

        // Return a success message
        return ApiResponse::success("Type deleted");
    }
}
