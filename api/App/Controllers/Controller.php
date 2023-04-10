<?php

namespace App\Controllers;

use App\Requests\Request;
use Exception;
use Model\Token;

/**
 * Summary of Controller
 */
abstract class Controller extends Request
{
    /**
     * @throws Exception
     */
    protected function checkToken($id): void
    {
        $token = new Token();
        $token = $token->getByToken(self::getBearerToken());

        if ($token->getUserId() !== $id && $token->getUser() && !$token->getUser()->isAdmin()) {
            throw new Exception("You don't have permission to access this resource", self::FORBIDDEN);
        }
    }
}
