<?php

namespace App\Controllers;

use App\Requests\Validator;
use App\Requests\ApiResponse;
use Exception;
use PDOException;
use Model\User;

/**
 * Summary of UserController
 */
class UserController
{
    private User $user;

    public function __construct()
    {
        $this->user = new User();
    }

    /**
     * This function fetches all users
     * 
     * @return string A JSON array of objects string encoded with all users
     */
    public function index(): string
    {
        // Get the all users from the database or fail on error.
        try {
            $users = $this->user->all();
        } catch (Exception $e) {
            return ApiResponse::abort($e->getCode(), $e->getMessage());
        }

        // Return the users as a JSON array of objects, as a success.
        return ApiResponse::success($users);
    }

    /**
     * Find a user by id
     *
     * @param int $id
     * @return string A JSON object string encoded with an user
     */
    public function show(int $id): string
    {
        // Get the user from the database or fail if not found or on error.
        try {
            $user = $this->user->get($id);
        } catch (Exception $e) {
            return ApiResponse::abort($e->getCode(), $e->getMessage());
        }

        // Return the user as a JSON object, as a success.
        return ApiResponse::success($user);
    }

    /**
     * This function creates a new user or update an existing user
     * 
     * @param string $name
     * @param string $email
     * @param string $password
     * @param string $confirmation
     * @param ?int $id
     * @return string
     */
    public function store(string $name, string $email, string $password, string $confirmation, ?int $id = null): string
    {
        //Validate the data
        $validator = new Validator($this->user, [
            "name" => $name,
            "email" => $email,
            "password" => $password,
            "confirmation" => $confirmation
        ]);

        //Check if the validation fails
        if ($validator->fails()) {
            //Abort the request with the error message
            return ApiResponse::abort(ApiResponse::BAD_REQUEST, $validator->errors());
        }

        try {
            //Get the validated data
            $safe = $validator->validated();

            $action = "created";

            $user = new User();
            $user->setName($safe["name"]);
            $user->setEmail($safe["email"]);
            if (empty($id)) {
                $user->setPassword($safe["password"]);
            } else {
                $user->setId($id);
                $action = "updated";
            }

            $message = "User {$action} successfully.";

            //Save or update the user in the database, 
            //sending the data as an array of parameters for the prepared statement
            //and id if is an update
            return ApiResponse::success($this->user->saveOrUpdate([
                ":name" => $user->getName(),
                ":email" => $user->getEmail(),
                ":password" => $user->getPassword()
            ], $id) . " " . $message);
        } catch (Exception $e) {
            //Abort the request with the error message
            return ApiResponse::abort($e->getCode(), $e->getMessage());
        } catch (PDOException $e) {
            //Abort the request with the error message
            return ApiResponse::abort($e->getCode(), $e->getMessage());
        }
    }


    /**
     * Update a user
     *
     * @param int $id
     * @param ?string $name
     * @param ?string $email
     * @return string
     */
    public function update(int $id, ?string $name = null, ?string $email = null): string
    {

        // Get the user's data from the database
        $parameters = $this->user->get($id);

        // Set the User object's properties
        $name = empty($name) ? $parameters["name"] : $name;
        $email = empty($email) ? $parameters["email"] : $email;
        $this->user->setId($id);
        $password = $this->user->getPassword();

        // Update the user in the database
        return $this->store($name, $email, $password, $password, $id);
    }

    /**
     * Delete a user
     * 
     * @param int $id
     * @return string
     */
    public function delete(int $id): string
    {
        // Delete the user from the database
        try {
            //Look if the user exists
            $this->user->get($id);
            $this->user->delete($id);
        } catch (Exception $e) {
            return ApiResponse::abort($e->getCode(), $e->getMessage());
        }

        // Return a success message
        return ApiResponse::success("User deleted");
    }
}
