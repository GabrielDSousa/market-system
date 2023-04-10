<?php

namespace Model;

class Type extends Model
{
    /**
     * The array of model visible attributes.
     *
     * @var array
     */
    protected array $visible = [
        "id",
        "name",
        "tax"
    ];

    /**
     * The array of model fillable attributes.
     *
     * @var array
     */
    protected array $fillable = [
        "name",
        "tax"
    ];

    /**
     * The rules to validate when adding a new User.
     *
     * @var array
     */
    protected array $rules = [
        "id" => "integer",
        "name" => "required|string|unique:type,name",
        "tax" => "required|integer"
    ];

    /**
     * Type name
     * @var string
     */
    protected string $name;

    /**
     * Type tax
     * @var int
     */
    protected int $tax;

    public function __construct(
        string $name = "",
        int $tax = 0,
        int $id = 0
    ) {
        parent::__construct();
        $this->setName($name);
        $this->setTax($tax);
        $this->setId($id);
    }

    /**
     * @param string $name
     * @return self
     */
    public function setName(string $name): self
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param int $tax
     * @return self
     */
    public function setTax(int $tax): self
    {
        $this->tax = $tax;
        return $this;
    }

    /**
     * @return int
     */
    public function getTax(): int
    {
        return $this->tax;
    }
}
