<?php

namespace App\Middleware;

use Exception;
use Model\Token;

class Auth implements Middleware
{
    /**
     * @param string $bearer
     * @return Auth
     * @throws Exception
     */
    public function handle(string $bearer): self
    {
        $token = new Token();
        $token = $token->getByToken($bearer);
        $token->tokenIsValid();

        return $this;
    }
}
