<?php

namespace Model;

use Model\Model;


/**
 * Summary of User
 */
class Type extends Model
{
    /**
     * Summary of table
     * @var string
     */
    protected string $table = "types";

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
        "name" => "required|string",
        "tax" => "required|integer"
    ];

    /**
     * Type name
     * @var ?string
     */
    private ?string $name;

    /**
     * Type tax
     * @var ?int
     */
    private ?int $tax;

    public function __construct(
        ?string $name = null,
        ?int $tax = null,
        ?int $id = null
    ) {
        parent::__construct();
        !empty($name) ?? $this->setName($name);
        !empty($tax) ?? $this->setTax($tax);
        !empty($id) ?? $this->id = $this->setId($id);
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
