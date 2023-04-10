<?php

namespace App\Controllers;

use App\Requests\Validator;
use Exception;
use Model\Type;

/**
 * Summary of TypeController
 */
class TypeController extends Controller
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
     * @throws Exception
     */
    public function index(): string
    {
        // Get the all types from the database or fail on error.
        $types = $this->type->all();

        // Return the types as a JSON array of objects, as a success.
        return self::success($types);
    }

    /**
     * Find a type by id
     *
     * @param int $id
     * @return string A JSON object string encoded with a type
     * @throws Exception
     */
    public function show(int $id): string
    {
        // Get the type from the database or fail if not found or on error.
        $type = $this->type->get($id);

        // Return the type as a JSON object, as a success.
        return self::success($type->toArray());
    }

    /**
     * This function creates a new type or update an existing type
     *
     * @param string $name
     * @param int $tax
     * @param int|null $id
     * @return string
     * @throws Exception
     */
    public function store(string $name, int $tax, int $id = null): string
    {
        //Validate the data
        $validator = new Validator($this->type->getRules(), [
            "name" => $name,
            "tax" => $tax
        ]);

        //Check if the validation fails
        if ($validator->fails()) {
            //Abort the request with the error message
            return self::abort($validator->errors(), self::BAD_REQUEST);
        }

        //Get the validated data
        $safe = $validator->validated();

        $type = new Type();
        $type->setName($safe["name"]);
        $type->setTax($safe["tax"]);

        if ($safe["id"]) {
            $type->setId($id);
        }

        //Save or update the type in the database,
        //sending the data as an array of parameters for the prepared statement
        //and id if is an update

        $this->type = $this->type->saveOrUpdate([
            ":name" => $type->getName(),
            ":tax" => $type->getTax()
        ]);

        return self::success(
            $this->type->toArray(),
            self::CREATED
        );
    }


    /**
     * Update a type
     *
     * @param int $id
     * @param string|null $name
     * @param int|null $tax
     * @return string
     * @throws Exception
     */
    public function update(int $id, string $name = null, int $tax = null): string
    {
        // Get the type's data from the database
        $type = $this->type->get($id);

        // Set the Product object's properties
        $name = empty($name) ? $type->getName() : $name;
        $tax = empty($tax) ? $type->getTax() : $tax;

        $this->type->setId($id);
        $this->type->setName($name);
        $this->type->setTax($tax);

        // Update the product in the database
        return $this->store(
            $this->type->getName(),
            $this->type->getTax(),
            $this->type->getId(),
        );
    }

    /**
     * Delete a type
     *
     * @param int $id
     * @return string
     * @throws Exception
     */
    public function delete(int $id): string
    {
        //Look if the type exists
        $type = $this->type->get($id);

        // Delete the type from the database
        $type->delete();

        // Return a success message
        return self::success("Type deleted");
    }
}
