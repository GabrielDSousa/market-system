<?php

namespace Model;

use App\Requests\Request;
use Database\Database;
use Exception;

/**
 * Summary of Model
 */
abstract class Model implements ModelInterface
{
    /**
     * The database connection
     *
     * @var Database
     */
    protected Database $db;

    /**
     * The database environment configuration array
     *
     * @var array
     */
    protected array $config;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected string $table;

    /**
     * The array of model visible attributes.
     *
     * @var array
     */
    protected array $visible = [];

    /**
     * The array of model fillable attributes.
     *
     * @var array
     */
    protected array $fillable = [];

    /**
     * The rules to validate when adding a new Model.
     * @var array
     */
    protected array $rules = [];

    /**
     * The attributes to be selected.
     * @var string
     */
    private string $attributes;

    /**
     * The attributes to be fill.
     * @var string
     */
    private string $fill;

    /**
     * The attributes to be fill at prepared statement.
     * @var string
     */
    private string $ps;

    /**
     * The model ID
     * @var int
     */
    protected int $id = 0;

    /**
     * @var string
     */
    private string $modelName;

    public function __construct()
    {
        $this->attributes = !empty($this->visible) ? implode(", ", $this->visible) : "*";
        $this->fill = implode(",", $this->fillable);
        $this->ps = " :" . implode(", :", $this->fillable);
        $this->config = config("database");
        $this->db = new Database($this->config);
        $this->modelName = static::class;
        $namespace = explode("\\", static::class);
        $this->table = $this->table ?? strtolower(end($namespace)) . 's';
    }

    /**
     * This function fetches all records of the model
     *
     * @return array An array of objects of the model
     * @throws Exception
     */
    public function all(): array
    {
        $params = $this->db->query("SELECT $this->attributes FROM public.$this->table")->findAllOrFail();
        $models = [];
        foreach ($params as $param) {
            $model = new static();
            $model->setAttributes($param);
            $models[] = $model->toArray();
        }

        return $models;
    }

    /**
     * This function fetches a record of the model given an id.
     *
     * @param int $id
     * @return self
     * @throws Exception
     */
    public function get(int $id): self
    {
        // Get the one record from the database given the id or fail on error.
        try {
            $params = $this->getByColumn("id", $id)->findOrFail();
        } catch (Exception $e) {
            if ($e->getCode() === Request::NOT_FOUND) {
                throw new Exception("The $this->modelName with id $id does not exist", Request::NOT_FOUND);
            }
            throw $e;
        }

        $this->setAttributes($params);

        return $this;
    }

    /**
     * This function save a record of the model.
     *
     * @param array $data
     * @return self
     * @throws Exception
     */
    public function save(array $data): self
    {
        //Insert the data in the database
        $this->db->query("INSERT INTO public.$this->table ($this->fill) VALUES ($this->ps)", $data)->executeOrFail();

        return $this->get($this->db->lastInsertId());
    }

    /**
     * This function update a record of the model.
     *
     * @param array $data
     * @return self
     * @throws Exception
     */
    public function update(array $data): self
    {
        //Update the data in the database
        $set = array_combine($this->fillable, array_map(fn($value) => "$value = :$value", $this->fillable));
        $fill = implode(", ", $set);
        $this->db->query(
            "UPDATE public.$this->table SET $fill WHERE id = :id",
            array_merge($data, [":id" => $this->id])
        )->executeOrFail();

        return $this;
    }

    /**
     * This function delete a record of the model.
     *
     * @return bool
     * @throws Exception
     */
    public function delete(): bool
    {
        //Delete the data in the database
        return $this->db->query("DELETE FROM public.$this->table WHERE id = :id", [":id" => $this->id])->executeOrFail(
            )->rowCount() === 1;
    }

    /**
     * This function save or update a record of the model.
     *
     * @param array $data
     * @return self
     * @throws Exception
     */
    public function saveOrUpdate(array $data): self
    {
        if ($this->id !== 0) {
            return $this->update($data);
        }

        return $this->save($data);
    }

    /**
     * This function fetches a record of the model given a column.
     *
     * @param string $name
     * @param string $value
     * @return Database A statement of records of the model
     * @throws Exception
     */
    public function getByColumn(string $name, string $value): Database
    {
        try {
            // Get the one record from the database given the column name and value  or fail on error.
            return $this->db->query(
                "SELECT $this->attributes FROM public.$this->table WHERE $name = :$name",
                [":$name" => $value]
            );
        } catch (Exception $e) {
            if ($e->getCode() === Request::NOT_FOUND) {
                throw new Exception("The $this->modelName with id $id does not exist", Request::NOT_FOUND);
            }
            throw $e;
        }
    }

    /**
     * @param array $params
     * @return self
     */
    protected function setAttributes(array $params): self
    {
        foreach ($this->visible as $attribute) {
            if (isset($params[$attribute])) {
                $this->$attribute = $params[$attribute];
            }
        }

        return $this;
    }

    /**
     * @param int $id
     * @return self
     */
    public function setId(int $id): self
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return array
     */
    public function getRules(): array
    {
        return $this->rules;
    }

    /**
     * @return string
     */
    public function toString(): string
    {
        $attributes = [];
        foreach ($this->visible as $attribute) {
            $attributes[] = "$attribute: $this->$attribute";
        }

        return implode(", ", $attributes);
    }

    public function toArray(): array
    {
        $attributes = [];

        foreach ($this->visible as $attribute) {
            $attributes[$attribute] = $this->$attribute;
        }

        return $attributes;
    }

    public function __toString()
    {
        return json_encode($this);
    }
}
