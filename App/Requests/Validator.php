<?php

namespace App\Requests;

use Error;
use Exception;
use Model\Model;

/**
 * Summary of Validator
 */
class Validator extends Request
{
    /**
     * Array of errors
     * @var array
     */
    private array $errors;

    /**
     * Array of rules
     * @var array
     */
    private array $rules;

    /**
     * Array of validated data
     * @var array
     */
    private array $validated;

    /**
     * Array of data to validate
     * @var array|null
     */
    private ?array $data;

    /**
     * @param array $data
     * @param array $rules
     * @throws Exception
     */
    public function __construct(array $rules, array $data)
    {
        $this->data = $data;
        $this->rules = $rules;
        $this->errors = [];
        $this->validated = [];
        $this->validate();
    }

    /**
     * Check if the field is empty.
     *
     * @param string $key The form field name.
     * @return void
     */
    private function required(string $key): void
    {
        // Check if the field exists in the form data
        if (!isset($this->data[$key]) || empty($this->data[$key])) {
            // Add an error to the error list
            $this->errors[$key] = ["required" => "The field $key is required."];
        }
    }

    /**
     * Checks if the field is a valid email
     *
     * @param string $key
     * @return void
     */
    private function email(string $key): void
    {
        if (isset($this->data[$key]) && !filter_var($this->data[$key], FILTER_VALIDATE_EMAIL)) {
            $this->errors[$key] = ["email" => "The field $key must be a valid email."];
        }
    }

    /**
     * Checks if the field is unique given the column name.
     *
     * @param string $key
     * @param string $rule
     * @return void
     * @throws Exception
     */
    private function unique(string $key, string $rule): void
    {
        // If the data does not contain the key, return.
        if (!isset($this->data[$key])) {
            return;
        }

        // Get the column name from the rule.
        [$model, $column] = explode(",", explode(":", $rule)[1]);
        $model = ucfirst($model);
        try {
            $model = new ("Model\\$model");
        } catch (Exception|Error $e) {
            throw new Exception("The model $model does not exist.");
        }


        // Get the value from the data.
        $value = $this->data[$key];

        // Check if the value is unique, passing in the id if it is an update.
        /** @var Model $model */
        $count = $model->getByColumn($column, $value)->rowCount();
        $unique = $count == 0;

        if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
            $unique = $count <= 1;
        }

        // If the value is unique, return.
        if ($unique === true) {
            return;
        }

        // If the value is not unique, but the check was successful, add an error.
        $this->errors[$key] = ["unique" => "The field $key must be unique."];
    }

    /**
     * Validate that the data has the minimum number of characters
     *
     * @param string $key
     * @param string $rule
     * @return void
     * @throws Exception
     */
    private function min(string $key, string $rule): void
    {
        // Get the minimum number of characters from the rule
        $minLength = explode(":", $rule)[1];
        if (!is_numeric($minLength)) {
            throw new Exception("The minimum length must be a number");
        }
        // If the data has fewer characters than the minimum, add an error
        if (isset($this->data[$key]) && strlen($this->data[$key]) < $minLength) {
            $this->errors[$key] = ["min" => "The field $key must be at least $minLength characters."];
        }
    }

    /**
     * Validate that the data has the maximum number of characters
     *
     * @param string $key
     * @param string $rule
     * @return void
     * @throws Exception
     */
    private function max(string $key, string $rule): void
    {
        // Get the maximum number of characters from the rule
        $maxLength = explode(":", $rule)[1];
        if (!is_numeric($maxLength)) {
            throw new Exception("The maximum length must be a number");
        }
        // If the data has fewer characters than the minimum, add an error
        if (isset($this->data[$key]) && strlen($this->data[$key]) > $maxLength) {
            $this->errors[$key] = ["max" => "The field $key must be at least $maxLength characters."];
        }
    }

    /**
     * Validate if the value of the current field matches the value
     * of the field specified in the rule.
     *
     * @param string $key
     * @param string $rule
     * @return void
     */
    private function same(string $key, string $rule): void
    {
        // If the current field is not set, we can return early
        if (!isset($this->data[$key])) {
            return;
        }

        // Get the field to check against
        $same = explode(":", $rule)[1];

        // If the field to check against does not exist, we can return early
        if (!isset($this->data[$same])) {
            return;
        }

        // If the value of the current field matches the value of the field to check
        // against, we can return early
        if ($this->data[$key] === $this->data[$same]) {
            return;
        }

        // If we got this far, it means the value of the current field does not match
        // the value of the field to check against, so we add an error to the errors
        // array
        $this->errors[$key] = ["same" => "The field $key must be the same as $same."];
    }

    /**
     * Validate that the data is a string.
     *
     * @param string $key
     * @return void
     */
    private function string(string $key): void
    {
        // Check if the key exists in the data
        if (isset($this->data[$key])) {
            // Check if the value of the key is not a string
            if (!is_string($this->data[$key])) {
                // Add an error message
                $this->errors[$key] = ["string" => "The field $key must be a string."];
            }
        }
    }

    /**
     * Checks if the data key is a boolean.
     *
     * @param string $key The data key to check.
     * @return void
     */
    private function boolean(string $key): void
    {
        // Check if the data key exists
        if (isset($this->data[$key])) {
            // Check if the data key is not equal to "true" or "false"
            if (!is_bool($this->data[$key])) {
                // Set the error for the key
                $this->errors[$key] = ["boolean" => "The field $key must be a boolean."];
            }
        }
    }

    private function integer(string $key): void
    {
        // Check if the data key exists
        if (isset($this->data[$key])) {
            // Check if the data key is not equal to "true" or "false"
            if (!is_int($this->data[$key])) {
                // Set the error for the key
                $this->errors[$key] = ["integer" => "The field $key must be an integer."];
            }
        }
    }

    private function float(string $key): void
    {
        // Check if the data key exists
        if (isset($this->data[$key])) {
            // Check if the data key is not equal to "true" or "false"
            if (!is_float($this->data[$key])) {
                // Set the error for the key
                $this->errors[$key] = ["float" => "The field $key must be a float."];
            }
        }
    }

    /**
     * Validate the data
     *
     * @return void
     * @throws Exception
     */
    private function validate(): void
    {
        //Loop through the rules and perform validation
        foreach ($this->rules as $key => $value) {
            $rules[$key] = explode("|", $value);
            foreach ($rules[$key] as $rule) {
                if ($rule == "required") {
                    $this->required($key);
                }
                if ($rule == "email") {
                    $this->email($key);
                }
                if (str_contains($rule, "unique")) {
                    $this->unique($key, $rule);
                }
                if (str_contains($rule, "min")) {
                    $this->min($key, $rule);
                }
                if (str_contains($rule, "max")) {
                    $this->max($key, $rule);
                }
                if (str_contains($rule, "same")) {
                    $this->same($key, $rule);
                }
                if ($rule == "boolean") {
                    $this->boolean($key);
                }
                if ($rule == "string") {
                    $this->string($key);
                }
                if ($rule == "integer") {
                    $this->integer($key);
                }
                if ($rule == "float") {
                    $this->float($key);
                }
            }
        }
    }


    /**
     * Set the validated data, filtering it if necessary
     *
     * @return void
     */
    private function filter(): void
    {
        foreach ($this->rules as $key => $value) {
            $rules[$key] = explode("|", $value);
            foreach ($rules[$key] as $rule) {
                if ($rule == "string") {
                    $this->validated[$key] = strip_tags($this->data[$key]);
                    $this->validated[$key] = htmlspecialchars($this->data[$key]);
                } elseif ($rule == "integer") {
                    $this->validated[$key] = filter_var($this->data[$key], FILTER_VALIDATE_INT);
                } elseif ($rule == "float") {
                    $this->validated[$key] = filter_var($this->data[$key], FILTER_VALIDATE_FLOAT);
                } elseif ($rule == "boolean") {
                    $this->validated[$key] = filter_var($this->data[$key], FILTER_VALIDATE_BOOLEAN);
                } elseif ($rule == "url") {
                    $this->validated[$key] = filter_var($this->data[$key], FILTER_VALIDATE_URL);
                } elseif ($rule == "email") {
                    $this->validated[$key] = filter_var($this->data[$key], FILTER_VALIDATE_EMAIL);
                } else {
                    $this->validated[$key] = $this->data[$key];
                }
            }
        }
    }

    /**
     * Returns true if there are any errors in the errors array, otherwise returns false
     *
     * @return bool
     */
    public function fails(): bool
    {
        if (count($this->errors) > 0) {
            //there are errors, so return true
            return true;
        }
        //there are no errors, so return false
        return false;
    }

    /**
     * Returns the array of errors
     *
     * @return array
     */
    public function errors(): array
    {
        return $this->errors;
    }

    /**
     * Returns the filtered data if the validation passes, null otherwise.
     *
     * @return array|null
     */
    public function validated(): ?array
    {
        // If the validation fails, return null
        if ($this->fails()) {
            return null;
        }

        // Filter the validated data
        $this->filter();

        // Return the filtered data
        return $this->validated;
    }
}
