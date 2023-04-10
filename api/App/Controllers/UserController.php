<?php

namespace App\Controllers;

use App\Requests\Validator;
use Exception;
use Model\Token;
use Model\User;

/**
 * Summary of UserController
 */
class UserController extends Controller
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
     * @throws Exception
     */
    public function index(): string
    {
        // Get the all users from the database or fail on error.
        $users = $this->user->all();

        // Return the users as a JSON array of objects, as a success.
        return self::success($users);
    }

    /**
     * Find a user by id
     *
     * @param int $id
     * @return string A JSON object string encoded with a user
     * @throws Exception
     */
    public function show(int $id): string
    {
        $this->checkToken($id);

        // Get the user from the database or fail if not found or on error.
        $user = $this->user->get($id);

        $token = new Token();
        $token = $token->getByToken(self::getBearerToken());

        if ($token->getUserId() != $id) {
            throw new Exception("You don't have permission to access this resource", self::UNAUTHORIZED);
        }

        // Return the user as a JSON object, as a success.
        return self::success($user->toArray());
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
     * @throws Exception
     */
    public function store(
        string $name,
        string $email,
        string $password,
        string $confirmation,
        ?int $id = null
    ): string {
        //Validate the data
        $validator = new Validator($this->user->getRules(), [
            "name" => $name,
            "email" => $email,
            "password" => $password,
            "confirmation" => $confirmation,
            "id" => $id ?? null
        ]);

        //Check if the validation fails
        if ($validator->fails()) {
            //Abort the request with the error message
            return self::abort($validator->errors(), self::BAD_REQUEST);
        }

        //Get the validated data
        $safe = $validator->validated();

        //Create a new User object
        $user = new User();
        if (!empty($safe['id'])) {
            $user = $this->user->get($id);
        }
        if ($user->getName() !== $safe['name']) {
            $user->setName($safe["name"]);
        }
        if ($user->getEmail() !== $safe['email']) {
            $user->setEmail($safe["email"]);
        }
        if (password_get_info($safe['password'])["algoName"] !== "bcrypt") {
            $user->setPassword($safe["password"]);
        }

        $password = password_get_info(
            $safe['password']
        )["algoName"] === "bcrypt" ? $safe["password"] : $user->getPassword();

        $this->user = $user->saveOrUpdate([
            ":name" => $user->getName(),
            ":email" => $user->getEmail(),
            ":password" => $password,
            ":admin" => $user->isAdmin() ? 1 : 0,
        ]);

        $this->user = $this->user->getByEmail($this->user->getEmail());

        //Save or update the user in the database,
        //sending the data as an array of parameters for the prepared statement
        //and id if is an update
        return self::success(
            $this->user->toArray(),
            self::CREATED
        );
    }

    /**
     * Update a user
     *
     * @param int $id
     * @param ?string $name
     * @param ?string $email
     * @return string
     * @throws Exception
     */
    public function update(int $id, ?string $name = null, ?string $email = null, ?string $password = null): string
    {
        $this->checkToken($id);

        // Get the user's data from the database
        $user = $this->user->get($id);

        // Set the Product object's properties
        $name = empty($name) ? $user->getName() : $name;
        $email = empty($email) ? $user->getEmail() : $email;

        $this->user->setId($id);
        $this->user->setName($name);
        $this->user->setEmail($email);

        if (!empty($password)) {
            $this->user->setPassword($password);
        } else {
            $this->user->setPassword($user->getPassword());
        }

        // Update the product in the database
        return $this->store(
            $this->user->getName(),
            $this->user->getEmail(),
            $this->user->getPassword(),
            $this->user->getPassword(),
            $this->user->getId(),
        );
    }

    /**
     * Delete a user
     *
     * @param int $id
     * @return string
     * @throws Exception
     */
    public function delete(int $id): string
    {
        $this->checkToken($id);

        // Get the user from the database or fail if not found or on error.
        $user = $this->user->get($id);

        // Delete the user from the database
        $user->delete();

        // Return a success message
        return self::success("User deleted");
    }
}
