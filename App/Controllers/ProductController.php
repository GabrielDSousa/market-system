<?php

namespace App\Controllers;

use App\Requests\Validator;
use Exception;
use Model\Product;

/**
 * Summary of ProductController
 */
class ProductController extends Controller
{
    private Product $product;

    public function __construct()
    {
        $this->product = new Product();
    }

    /**
     * This function fetches all products
     *
     * @return string A JSON array of objects string encoded with all products
     * @throws Exception
     */
    public function index(): string
    {
        // Get the all products from the database or fail on error.
        $products = $this->product->all();

        // Return the products as a JSON array of objects, as a success.
        return self::success($products);
    }

    /**
     * Find a product by id
     *
     * @param int $id
     * @return string A JSON object string encoded with a product
     * @throws Exception
     */
    public function show(int $id): string
    {
        // Get the product from the database or fail if not found or on error.
        $product = $this->product->get($id);

        // Return the product as a JSON object, as a success.
        return self::success($product->toArray());
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
     * @throws Exception
     */
    public function store(string $name, int $value, int $type_id, ?string $description = null, ?int $id = null): string
    {
        //Validate the data
        $validator = new Validator($this->product->getRules(), [
            "name" => $name,
            "value" => $value,
            "type_id" => $type_id,
            "description" => $description
        ]);

        //Check if the validation fails
        if ($validator->fails()) {
            //Abort the request with the error message
            return self::abort($validator->errors(), self::BAD_REQUEST);
        }

        //Get the validated data
        $safe = $validator->validated();

        $product = new Product();
        $product->setName($safe["name"]);
        $product->setDescription($safe["description"]);
        $product->setValue($safe["value"]);
        $product->setTypeId($safe["type_id"]);
        //Check if the type exists
        $product->getType();
        
        if (!empty($id)) {
            $product->setId($id);
        }

        $this->product = $this->product->saveOrUpdate([
            ":name" => $product->getName(),
            ":description" => $product->getDescription(),
            ":value" => $product->getValue(),
            ":type_id" => $product->getTypeId()
        ]);

        //Save or update the product in the database,
        //sending the data as an array of parameters for the prepared statement
        //and id if is an update

        return self::success($this->product->toArray(), self::CREATED);
    }


    /**
     * Update a product
     *
     * @param int|null $id
     * @param null|string $name
     * @param null|int $value
     * @param null|int $type_id
     * @param null|string $description
     * @return string
     * @throws Exception
     */
    public function update(
        int $id = null,
        ?string $name = null,
        ?int $value = null,
        ?int $type_id = null,
        ?string $description = null
    ): string {
        // Get the product's data from the database
        $product = $this->product->get($id);

        // Set the Product object's properties
        $name = empty($name) ? $product->getName() : $name;
        $value = empty($value) ? $product->getValue() : $value;
        $type_id = empty($type_id) ? $product->getTypeId() : $type_id;
        $description = empty($description) ? $product->getDescription() : $description;

        $this->product->setId($id);
        $this->product->setName($name);
        $this->product->setValue($value);
        $this->product->setTypeId($type_id);
        $this->product->setDescription($description);

        // Update the product in the database
        return $this->store(
            $this->product->getName(),
            $this->product->getValue(),
            $this->product->getTypeId(),
            $this->product->getDescription(),
            $this->product->getId()
        );
    }

    /**
     * Delete a product
     *
     * @param int $id
     * @return string
     * @throws Exception
     */
    public function delete(int $id): string
    {
        //Look if the product exists
        $this->product = $this->product->get($id);

        // Delete the product from the database
        $this->product->delete();

        // Return a success message
        return self::success("Product deleted");
    }
}
