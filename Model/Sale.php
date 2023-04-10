<?php

namespace Model;

use App\Requests\Request;
use Exception;

class Sale extends Model implements ModelInterface
{
    /**
     * The array of model visible attributes.
     *
     * @var array
     */
    protected array $visible = [
        "id",
        "date",
        "cart",
        "value",
        "tax",
        "total",
        "user_id"
    ];

    /**
     * The array of model fillable attributes.
     *
     * @var array
     */
    protected array $fillable = [
        "cart",
        "value",
        "tax",
        "total",
        "user_id"
    ];

    /**
     * The rules to validate when adding a new User.
     *
     * @var array
     */
    protected array $rules = [
        "id" => "integer",
        "cart" => "required|string",
        "value" => "required|string",
        "tax" => "required|string",
        "total" => "required|string",
        "user_id" => "required|integer"
    ];

    /**
     * Sold items in a json string
     * @var string
     */
    protected string $cart;

    /**
     * Value without tax
     * @var string
     */
    protected string $value;

    /**
     * Tax value
     * @var string
     */
    protected string $tax;

    /**
     * Total of the sale
     * @var string
     */
    protected string $total;

    /**
     * user id that made the sale
     * @var int
     */
    protected int $user_id;

    /**
     * User that made the sale
     * @var User
     */
    protected User $user;

    /**
     * Product constructor.
     *
     * @param string $cart
     * @param string $value
     * @param string $tax
     * @param string $total
     * @param int $user_id
     * @param int|null $id
     *
     * @return void
     * @throws Exception
     */
    public function __construct(
        string $cart = "",
        string $value = "",
        string $tax = "",
        string $total = "",
        int $user_id = 0,
        int $id = null
    ) {
        parent::__construct();

        $this->setCart($cart)
            ->setValue($value)
            ->setTax($tax)
            ->setTotal($total)
            ->setUserId($user_id);
        if ($id) {
            $this->setId($id);
        }
    }

    /**
     * @param string $cart
     * @return Sale
     */
    public function setCart(string $cart): Sale
    {
        $this->cart = $cart;
        return $this;
    }

    /**
     * @return string
     */
    public function getCart(): string
    {
        return $this->cart;
    }

    /**
     * @param string $value
     * @return Sale
     */
    public function setValue(string $value): Sale
    {
        $this->value = $value;
        return $this;
    }

    /**
     * @param string $tax
     * @return Sale
     */
    public function setTax(string $tax): Sale
    {
        $this->tax = $tax;
        return $this;
    }

    /**
     * @param string $total
     * @return Sale
     */
    public function setTotal(string $total): Sale
    {
        $this->total = $total;
        return $this;
    }

    /**
     * @param int $user_id
     * @return Sale
     */
    public function setUserId(int $user_id): Sale
    {
        $this->user_id = $user_id;
        return $this;
    }

    /**
     * @return User
     * @throws Exception
     */
    public function getUser(): User
    {
        if ($this->user_id === 0) {
            throw new Exception("User id is empty", Request::NOT_FOUND);
        }

        if (empty($this->user)) {
            $this->setUser((new User())->get($this->getUserId()));
        }

        return $this->user;
    }

    /**
     * @param User $user
     * @return Sale
     */
    private function setUser(User $user): Sale
    {
        $this->user = $user;
        return $this;
    }

    /**
     * @return string
     */
    public function getValue(): string
    {
        return $this->value;
    }

    /**
     * @return string
     */
    public function getTax(): string
    {
        return $this->tax;
    }

    /**
     * @return string
     */
    public function getTotal(): string
    {
        return $this->total;
    }

    /**
     * @return int
     */
    public function getUserId(): int
    {
        return $this->user_id;
    }

}
