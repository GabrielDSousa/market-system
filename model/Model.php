<?php
require "database/Database.php";
require "app/helpers.php";

/**
 * Summary of Model
 */
abstract class Model
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
    protected array $fillable;

    /**
     * The rules to validate when adding a new Model.
     * @var array
     */
    protected array $rules;

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

    public function __construct()
    {
        $this->attributes = !empty($this->visible) ? implode(", ", $this->visible) : "*";
        $this->fill = implode(",", $this->fillable);
        $this->ps = " :" . implode(", :", $this->fillable);
        $this->config = require "config/database.php";
        $this->db = new Database($this->config["database"]);
    }

    /**
     * This function fetches all records of the model
     * 
     * @return array A array of objects of the model
     */
    public function all(): array
    {
        return $this->db->query("SELECT {$this->attributes} FROM public.{$this->table}")->findAllOrFail();
    }

    /**
     * This function fetches a record of the model given an id.
     * 
     * @param int $id
     * @return array A array of a record of the model
     */
    public function get(int $id): array
    {
        try {
            return $this->getByColumn("id", $id)->findOrFail();
        } catch (Exception $e) {
            if ($e->getCode() == ApiResponse::NOT_FOUND) {
                throw new Exception("The {$this->table} with id {$id} was not found", ApiResponse::NOT_FOUND);
            }

            throw $e;
        }
    }

    /**
     * This function return if a column have an unique value.
     * 
     * @param string $column
     * @param string $value
     * @param bool $isUpdate default false
     * @return bool If the column don't have a record with the value return true, else return false
     */
    public function isUnique(string $name, string $value, bool $isUpdate = false): bool
    {
        try {
            $users = $this->getByColumn($name, $value)->findAllOrFail();
            if ($isUpdate && count($users) === 1 && $users[0]["id"] == $_POST["id"]) {
                return true;
            }
        } catch (PDOException $e) {
            throw $e;
        } catch (Exception $e) {
            if ($e->getCode() == ApiResponse::NOT_FOUND) {
                return true;
            }

            throw $e;
        }

        return false;
    }

    /**
     * This function fetches a record of the model given an column.
     * 
     * @param string $name
     * @param string $value
     * @return Database A statement of records of the model
     */
    private function getByColumn(string $name, string $value): Database
    {
        // Get the one record from the database given the column name and value  or fail on error.
        try {
            return $this->db->query("SELECT {$this->attributes} FROM public.{$this->table} WHERE {$name} = :{$name}", [":{$name}" => $value]);
        } catch (PDOException $e) {
            throw $e;
        } catch (Exception $e) {
            throw new Exception("Record of {$this->table} with {$name} equal {$value} not found", ApiResponse::NOT_FOUND);
        }
    }

    public function save(array $data): int
    {
        //Insert the data in the database
        try {
            return $this->db->query("INSERT INTO public.{$this->table} ({$this->fill}) VALUES ({$this->ps})", $data)->executeOrFail();
        } catch (PDOException $e) {
            throw $e;
        } catch (Exception $e) {
            throw new Exception("Error saving the record of {$this->table}", ApiResponse::INTERNAL_SERVER_ERROR);
        }
    }

    public function update(array $data, int $id): int
    {
        //Update the data in the database
        try {
            $set = array_combine($this->fillable, array_map(fn ($value) => "{$value} = :{$value}", $this->fillable));
            $fill = implode(", ", $set);
            return $this->db->query("UPDATE public.{$this->table} SET {$fill} WHERE id = :id",  array_merge($data, [":id" => $id]))->executeOrFail();
        } catch (PDOException $e) {
            throw $e;
        } catch (Exception $e) {
            throw new Exception("Error updating the record of {$this->table}", ApiResponse::INTERNAL_SERVER_ERROR);
        }
    }

    public function saveOrUpdate(array $data, int $id = null): int
    {
        if ($id) {
            return $this->update($data, $id);
        }

        return $this->save($data);
    }

    public function delete(int $id): int
    {
        //Delete the data in the database
        try {
            return $this->db->query("DELETE FROM public.{$this->table} WHERE id = :id", [":id" => $id])->executeOrFail();
        } catch (PDOException $e) {
            throw $e;
        } catch (Exception $e) {
            throw new Exception("Error deleting the record of {$this->table}", ApiResponse::INTERNAL_SERVER_ERROR);
        }
    }
}
