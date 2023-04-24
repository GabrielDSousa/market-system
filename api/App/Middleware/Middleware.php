<?php

namespace App\Middleware;

interface Middleware
{
    public const MAP = [
        "auth" => "App\Middleware\Auth",
        "admin" => "App\Middleware\Admin",
        "guest" => "App\Middleware\Guest"
    ];
}
