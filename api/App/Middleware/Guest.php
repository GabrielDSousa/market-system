<?php

namespace App\Middleware;

use App\Requests\ApiResponse;
use Exception;

class Guest implements Middleware
{
    /**
     * @param string|null $bearer
     * @return Guest
     * @throws Exception
     */
    public function handle(?string $bearer = null): self
    {
//        if (!empty($bearer)) {
//            throw new Exception("You are already logged in", Request::UNPROCESSABLE_ENTITY);
//        }

        return $this;
    }
}
