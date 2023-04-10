<?php

namespace App\Controllers;

use App\Requests\Validator;
use Exception;
use Model\Token;
use Model\User;

/**
 * This class is responsible for handling the authentication requests.
 */
class AuthController extends Controller
{
    /**
     * @var User
     */
    private User $user;

    /**
     * AuthController constructor.
     */
    public function __construct()
    {
        $this->user = new User();
    }

    /**
     * This method is responsible for handling the login request.
     *
     * @param string $email
     * @param string $password
     * @return string
     * @throws Exception
     */
    public function login(string $email, string $password): string
    {
        // Validate the request
        $validator = new Validator([
            'email' => 'required|email',
            'password' => 'required|min:6|max:32|string'
        ], [
            'email' => $email,
            'password' => $password
        ]);

        // Check if the validation failed
        if ($validator->fails()) {
            return self::abort($validator->errors(), self::BAD_REQUEST);
        }

        // Get the user from the database or fail if not found or on error.
        $this->user = $this->user->getByEmail($email);

        // Check if the password is correct
        if (!$this->user->verifyPassword($password)) {
            throw new Exception('Wrong credentials', self::UNAUTHORIZED);
        }

        // Create a new token or return an existing one.
        $token = new Token();
        $token->setUser($this->user);
        $token = $token->createToken();

        if ($token->getId() !== 0) {
            return self::success([
                'token' => $token->getToken(),
                "user" => $token->getUser()->toArray()
            ]);
        }

        // Save the token to the database or fail on error.
        $token = $token->saveOrUpdate([
            "token" => $token->getToken(),
            "user_id" => $token->getUser()->getId()
        ]);

        // Return the token as a JSON object, as a success.
        return self::success([
            "token" => $token->getToken(),
            "user" => $token->getUser()->toArray()
        ]);
    }

    /**
     * This method is responsible for handling the logout request.
     *
     * @param string $email
     * @return string
     * @throws Exception
     */
    public function logout(string $email): string
    {
        $bearer = self::getBearerToken();

        // Validate the request
        $validator = new Validator([
            'token' => 'required|string',
            'email' => 'required|email'
        ], [
            'token' => $bearer,
            'email' => $email
        ]);

        // Check if the validation failed
        if ($validator->fails()) {
            return self::abort($validator->errors(), self::BAD_REQUEST);
        }

        // Create a new token or return an existing one.
        $user = (new User())->getByEmail($email);

        $token = new Token();
        $token->getByToken($bearer);
        $token->setUser($user);

        $token = $token->tokenIsValid();

        $isDeleted = $token->delete();

        if (!$isDeleted) {
            throw new Exception("Something went wrong", self::INTERNAL_SERVER_ERROR);
        }

        return self::success("User logged out successfully");
    }
}
