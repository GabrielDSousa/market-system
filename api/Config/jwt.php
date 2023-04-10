<?php

// JWT environment variables
return [
    "jwt" => [
        "secret" => "secret",
        "expiration" => 60 * 60 * 24 * 30 // In seconds, for local testing, it's 30 days
    ]
];
