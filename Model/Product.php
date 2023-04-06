<?php

namespace Model;

use App\Requests\ApiResponse;
use Model\Model;
use Model\Type;


/**
 * Summary of User
 */
class Product extends Model
{
    /**
     * Summary of table
     * @var string
     */
    protected string $table = "products";

    /**
     * The array of model visible attributes.
     *
     * @var array
     */
    protected array $visible = [
        "id",
        "name",
        "description",
        "value",
        "type_id"
    ];

    /**
     * The array of model fillable attributes.
     *
     * @var array
     */
    protected array $fillable = [
        "name",
        "description",
        "value",
        "type_id"
    ];

    /**
     * The rules to validate when adding a new User.
     * 
     * @var array
     */
    protected array $rules = [
        "id" => "integer",
        "name" => "required|string",
        "description" => "string",
        "value" => "required|integer",
        "type_id" => "required|integer"
    ];

    /**
     * Product name
     * @var ?string
     */
    private ?string $name;

    /**
     * Product description
     * @var ?string
     */
    private ?string $description;

    /**
     * Product value
     * @var ?int
     */
    private ?int $value;

    /**
     * Product type id
     * @var ?int
     */
    private ?int $type_id;

    /**
     * Product type
     * @var Type
     */
    private Type $type;

    /**
     * Product constructor.
     * @param string|null $name
     * @param string|null $description
     * @param int|null $value
     * @param int|null $type_id
     * @param int|null $id
     */
    public function __construct(
        ?string $name = null,
        ?string $description = null,
        ?int $value = null,
        ?int $type_id = null,
        ?int $id = null
    ) {
        parent::__construct();
        !empty($name) ?? $this->setName($name);
        !empty($description) ?? $this->setDescription($description);
        !empty($value) ?? $this->setValue($value);
        !empty($type_id) ?? $this->setTypeId($type_id);
        !empty($id) ?? $this->id = $this->setId($id);
    }

    /**
     * @param string
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
     * Product description
     * @return string|null
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * Product description
     * @param string|null $description Product description
     * @return self
     */
    public function setDescription(?string $description): self
    {
        $this->description = $description;
        return $this;
    }

    /**
     * Product value
     * @return int|null
     */
    public function getValue(): ?int
    {
        return $this->value;
    }

    /**
     * Product value
     * @param int|null $value Product value
     * @return self
     */
    public function setValue(?int $value): self
    {
        $this->value = $value;
        return $this;
    }

    /**
     * Product type id
     * @return int|null
     */
    public function getTypeId(): ?int
    {
        return $this->type_id;
    }

    /**
     * Product type id
     * @param int|null $type_id Product type id
     * @return self
     */
    public function setTypeId(?int $type_id): self
    {
        $this->type_id = $type_id;

        $this->type = $this->getType();
        if (empty($this->type)) {
            throw new \Exception("Product type not found", ApiResponse::NOT_FOUND);
        }

        return $this;
    }

    /**
     * Product type
     * @return Type
     */
    public function getType(): Type
    {
        if (empty($this->type_id)) {
            throw new \Exception("Product type id is empty", ApiResponse::NOT_FOUND);
        }
        $parameters = (new Type())->get($this->type_id);
        if (empty($parameters)) {
            throw new \Exception("Product type doesn't exist", ApiResponse::NOT_FOUND);
        }
        $this->type = new Type(extract($parameters));
        return $this->type;
    }

    /**
     * Product type
     * @param Type $type Product type
     * @return self
     */
    public function setType(Type $type): self
    {
        $this->type = $type;
        return $this;
    }
}
