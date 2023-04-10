<?php

namespace Model;

use App\Requests\Request;
use Exception;

class Product extends Model implements ModelInterface
{
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
     * @var string
     */
    protected string $name;

    /**
     * Product description
     * @var string
     */
    protected string $description;

    /**
     * Product value
     * @var int
     */
    protected int $value;

    /**
     * Product type id
     * @var int
     */
    protected int $type_id;

    /**
     * Product type
     * @var Type
     */
    protected Type $type;

    /**
     * Product constructor.
     * @param string $name
     * @param string $description
     * @param int $value
     * @param int $type_id
     * @param int $id
     * @throws Exception
     */
    public function __construct(
        string $name = "",
        string $description = "",
        int $value = 0,
        int $type_id = 0,
        int $id = 0
    ) {
        parent::__construct();
        $this->setName($name);
        $this->setDescription($description);
        $this->setValue($value);
        $this->setTypeId($type_id);
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
     * Product description
     * @return string|null
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * Product value
     * @param int $value Product value
     * @return self
     */
    public function setValue(int $value): self
    {
        $this->value = $value;
        return $this;
    }

    /**
     * Product value
     * @return int
     */
    public function getValue(): int
    {
        return $this->value;
    }

    /**
     * Product type id
     * @param int $type_id Product type id
     * @return self
     * @throws Exception
     */
    public function setTypeId(int $type_id): self
    {
        $this->type_id = $type_id;

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
     * Product type
     * @return Type
     * @throws Exception
     */
    public function getType(): Type
    {
        if (empty($this->type_id)) {
            throw new Exception("Product type id is empty", Request::NOT_FOUND);
        }

        if (empty($this->type)) {
            $this->setType((new Type())->get($this->type_id));
        }

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
