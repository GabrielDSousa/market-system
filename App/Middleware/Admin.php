<?php

namespace App\Middleware;

use App\Requests\Request;
use Exception;
use Model\Token;

class Admin implements Middleware
{
    /**
     * @throws Exception
     */
    public function handle(string $bearer): self
    {
        $token = (new Token())->getByToken($bearer);

        $token->setUser($token->getUser());

        if (!$token->getUser()->isAdmin()) {
            throw new Exception("You are not an admin", Request::UNAUTHORIZED);
        }

        $token->tokenIsValid();

        return $this;
    }
}
