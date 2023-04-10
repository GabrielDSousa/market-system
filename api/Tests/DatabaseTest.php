<?php

declare(strict_types=1);


use PHPUnit\Framework\TestCase;

require "/home/gabs/projects/market-system/Database/Database.php";

use Database\Database;

/**
 * Summary of DatabaseTest
 */
final class DatabaseTest extends TestCase
{
    /**
     * @var array|string[]
     */
    private array $config;
    /**
     * @var Database
     */
    private Database $connection;

    public function setUp(): void
    {
        parent::setUp();

        $this->config = [
            "driver" => "pgsql",
            "host" => "localhost",
            "port" => "5432",
            "dbname" => "market",
            "user" => "postgres",
            "password" => "postgres"
        ];
    }


    /**
     * This test checks for a success Database object when the Database class is constructed
     * with a valid array for the configuration. It uses the assertInstanceOf() method
     * to verify if a Database connection was returned. The test will fail if a connection isn't returned.
     *
     * @return void
     */
    public function testClassConstructor()
    {
        $this->connection = new Database($this->config);

        // Assert that the connection is an instance of the Database class.
        $this->assertInstanceOf(
            Database::class,
            $this->connection
        );
    }


    /**
     * This test checks for an exception when the Database class is constructed with an empty
     * array for the configuration. It uses the expectException() method to set up the test
     * to expect an Exception to be thrown. The test will fail if an Exception is not thrown.
     *
     * @return void
     */
    public function testClassConstructorError()
    {
        $this->expectException(PDOException::class);
        new Database([
            "driver" => "pgsql",
            "host" => "localhost",
            "port" => "543222",
            "dbname" => "market",
            "user" => "postgres",
            "password" => "postgres"
        ]);
    }

    /**
     * This test checks for a success Database object when the Database class is constructed
     * with a valid array for the configuration. It uses the assertInstanceOf() method
     * to verify if a Database connection was returned.
     * Then the connection is used to check if the database schema exists
     * The $exists variable is an array that contains the schema name
     * The test uses the assertIsArray() method to verify if the $exists variable is an array
     * The test will fail if the $exists variable is not an array or if the array doesn't contain
     * the key "schema_name" or if the value of the key "schema_name" is not "public"
     *
     * @return void
     * @throws Exception
     */
    public function testQuery()
    {
        // Assert that the connection is an instance of the Database class.
        $this->assertInstanceOf(
            Database::class,
            $this->connection
        );

        // check if the public schema exists
        $exists = $this->connection->query("SELECT schema_name FROM information_schema.schemata WHERE schema_name = 'public'")->findAllOrFail()[0];

        // check if the result is an array
        $this->assertIsArray($exists);

        // check if the result array has the key "schema_name"
        $this->assertArrayHasKey("schema_name", $exists);

        // check if the result array has the value "public"
        $this->assertEquals("public", $exists["schema_name"]);
    }
}
