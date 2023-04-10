<?php

namespace App\Controllers;

use App\Requests\Validator;
use Exception;
use Model\Sale;

/**
 * Summary of ProductController
 */
class SaleController extends Controller
{
    private Sale $sale;

    public function __construct()
    {
        $this->sale = new Sale();
    }

    /**
     * This function fetches all sales
     *
     * @return string A JSON array of objects string encoded with all sales
     * @throws Exception
     */
    public function index(): string
    {
        // Get the all sales from the database or fail on error.
        $sales = $this->sale->all();

        // Return the sales as a JSON array of objects, as a success.
        return self::success($sales);
    }

    /**
     * Find a sale by id
     *
     * @param int $id
     * @return string A JSON object string encoded with a sale
     * @throws Exception
     */
    public function show(int $id): string
    {
        // Get the sale from the database or fail if not found or on error.
        $sale = $this->sale->get($id);

        // Return the sale as a JSON object, as a success.
        return self::success($sale->toArray());
    }

    /**
     * This function creates a new sale or update an existing sale
     * @param string $cart
     * @param string $value
     * @param string $tax
     * @param string $total
     * @param int $user_id
     * @param int|null $id
     * @return string
     * @throws Exception
     */
    public function store(
        array $cart,
        string $value,
        string $tax,
        string $total,
        int $user_id,
        int $id = null
    ): string {
        $cart = json_encode($cart);
        //Validate the data
        $validator = new Validator($this->sale->getRules(), [
            "cart" => $cart,
            "value" => $value,
            "tax" => $tax,
            "total" => $total,
            "user_id" => $user_id
        ]);

        //Check if the validation fails
        if ($validator->fails()) {
            //Abort the request with the error message
            return self::abort($validator->errors(), self::BAD_REQUEST);
        }

        //Get the validated data
        $safe = $validator->validated();

        $sale = new Sale();
        $sale->setCart($safe["cart"]);
        $sale->setValue($safe["value"]);
        $sale->setTax($safe["tax"]);
        $sale->setTotal($safe["total"]);
        $sale->setUserId($safe["user_id"]);

        //Check if the type exists
        $sale->getUser();

        if (!empty($id)) {
            $sale->setId($id);
        }

        $this->sale = $this->sale->saveOrUpdate([
            ":cart" => $sale->getCart(),
            ":value" => $sale->getValue(),
            ":tax" => $sale->getTax(),
            ":total" => $sale->getTotal(),
            ":user_id" => $sale->getUserId()
        ]);

        //Save or update the sale in the database,
        //sending the data as an array of parameters for the prepared statement
        //and id if is an update

        return self::success($this->sale->toArray(), self::CREATED);
    }


    /**
     * Update a sale
     *
     * @param int $id
     * @param string|null $cart
     * @param string|null $value
     * @param string|null $tax
     * @param string|null $total
     * @param int|null $user_id
     * @return string
     * @throws Exception
     */
    public function update(
        int $id,
        string $cart = null,
        string $value = null,
        string $tax = null,
        string $total = null,
        int $user_id = null
    ): string {
        // Get the sale's data from the database
        $sale = $this->sale->get($id);

        $cart = empty($cart) ? $sale->getCart() : $cart;
        $value = empty($value) ? $sale->getValue() : $value;
        $tax = empty($tax) ? $sale->getTax() : $tax;
        $total = empty($total) ? $sale->getTotal() : $total;
        $user_id = empty($user_id) ? $sale->getUserId() : $user_id;

        $this->sale->setCart($cart);
        $this->sale->setValue($value);
        $this->sale->setTax($tax);
        $this->sale->setTotal($total);
        $this->sale->setUserId($user_id);

        // Update the sale in the database
        return $this->store(
            $this->sale->getCart(),
            $this->sale->getValue(),
            $this->sale->getTax(),
            $this->sale->getTotal(),
            $this->sale->getUserId(),
            $this->sale->getId()
        );
    }

    /**
     * Delete a sale
     *
     * @param int $id
     * @return string
     * @throws Exception
     */
    public function delete(int $id): string
    {
        //Look if the sale exists
        $this->sale = $this->sale->get($id);

        // Delete the sale from the database
        $this->sale->delete();

        // Return a success message
        return self::success("Product deleted");
    }
}
