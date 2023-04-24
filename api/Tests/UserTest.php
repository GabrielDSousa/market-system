<?php

declare(strict_types=1);

namespace Tests;

use Model;
use PHPUnit\Framework\TestCase;

/**
 * Summary of UserTest
 */
final class UserTest extends TestCase
{
    /**
     * This test checks for a success User object when the User class is constructed
     * with a valid array for the configuration. It uses the assertInstanceOf() method
     * to verify if a User was returned. The test will fail if a User isn't returned.
     *
     * @return void
     */
    public function testClassConstructor()
    {
        // Create a User.
        $user = new Model\User();

        // Assert that the user is an instance of the User class.
        $this->assertInstanceOf(
            Model\User::class,
            $user
        );
    }
}
