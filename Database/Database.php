<?php

namespace Database;

use PDO;
use PDOStatement;
use PDOException;
use Exception;
use App\Requests\ApiResponse;

class Database
{
    private PDO $connection;
    private PDOStatement|bool $statement;

    /**
     * Creates a new instance of a database connection.
     *
     * The database connection is created using the information supplied in the
     * $config array. The following keys are mandatory in the array:
     * - driver: The name of the database driver (e.g. 'mysql', 'pgsql', etc)
     * - host: The name or IP address of the database host
     * - port: The port number on which the database server is listening
     * - database: The name of the database
     * - user: The user name for connecting to the database
     * - password: The password for connecting to the database
     *
     * @param array $config The configuration array
     */

    public function __construct(array $config)
    {
        $this->setConnection($config);
    }

    /**
     * Set the connection
     *
     * @param array $config
     * @return void
     */
    protected function setConnection(array $config): void
    {
        // Set the DSN (Data Source Name) for the database.
        $dsn = "{$config["driver"]}:
            host={$config["host"]};
            port={$config["port"]};
            dbname={$config["dbname"]}";

        // Set the user and password.
        $user = $config["user"];
        $password = $config["password"];

        // Set the options.
        $options = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        ];

        // Create a PDO instance using the DSN and other config data.
        try {
            $this->connection = new PDO(
                $dsn,
                $user,
                $password,
                $options
            );
        } catch (PDOException $e) {
            // If there is an error with the connection, throw an exception.
            throw new PDOException($e->getMessage(), (int)$e->getCode());
        }
    }

    /**
     * Execute a SQL query and return the result as a PDOStatement object.
     *
     * @param string $query The SQL query to execute.
     * @param array $parameters The parameters to bind to the query.
     * @return self The result of the query.
     */
    public function query(string $query, array $parameters = []): self
    {
        // Prepare the query for execution
        $this->statement = $this->connection->prepare($query);
        // Execute the query
        $this->statement->execute($parameters);
        // Return the result as a PDOStatement object
        return $this;
    }


    /**
     * This method executes the prepared statement and returns a record if it was found.
     * If the record was not found, it throws an exception.
     * If there was an error while executing the statement, it throws an exception.
     *
     * @return array The record found.
     */
    public function findOrFail(): array
    {
        try {
            // fetches a record from the database
            $record = $this->statement->fetch();

            // throws an exception if the result is empty
            if ($record == false) {
                throw new Exception(ApiResponse::MESSAGES[ApiResponse::NOT_FOUND], ApiResponse::NOT_FOUND);
            }

            return $record;
        } catch (PDOException $e) {
            // PDO error code 1064 is a syntax error in the query
            if ($e->errorInfo[1] == 1064) {
                throw new PDOException("There was a syntax error in the query", ApiResponse::INTERNAL_SERVER_ERROR);
            }
            // PDO error code 1049 is a error when the database doesn't exist
            if ($e->errorInfo[1] == 1049) {
                throw new PDOException("The database doesn't exist", ApiResponse::INTERNAL_SERVER_ERROR);
            }

            throw new PDOException("There was an error while trying to find the record", ApiResponse::INTERNAL_SERVER_ERROR);
        } catch (Exception $e) {
            throw $e; // If it's not a PDOException, just rethrow it
        }
    }

    /**
     * This method executes the prepared statement and returns the records if it was found.
     * If the records was not found, it throws an exception.
     * If there was an error while executing the statement, it throws an exception.
     *
     * @return array The records found.
     */
    public function findAllOrFail(): array
    {
        try {
            // fetches all the records from the database
            $result = $this->statement->fetchAll();

            // throws an exception if the result is empty
            if ($result == []) {
                throw new Exception("Records not found", ApiResponse::NOT_FOUND);
            }

            return $result;
        } catch (PDOException $e) {
            // PDO error code 1064 is a syntax error in the query
            if ($e->errorInfo[1] == 1064) {
                throw new Exception("There was a syntax error in the query", ApiResponse::INTERNAL_SERVER_ERROR);
            }

            // PDO error code 1049 is a error when the database doesn't exist
            if ($e->errorInfo[1] == 1049) {
                throw new Exception("The database doesn't exist", ApiResponse::INTERNAL_SERVER_ERROR);
            }

            throw new Exception("There was an error while trying to find all the records", ApiResponse::INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * This method executes the prepared statement and returns the number of affected rows.
     * If there was an error while executing the statement, it throws an exception.
     *
     * @return int The number of affected rows.
     */
    public function executeOrFail(): int
    {
        try {
            // returns the number of affected rows
            return $this->statement->rowCount();
        } catch (PDOException $e) {
            // PDO error code 1064 is a syntax error in the query
            if ($e->errorInfo[1] == 1064) {
                throw new Exception("There was a syntax error in the query", ApiResponse::INTERNAL_SERVER_ERROR);
            }

            // PDO error code 1049 is a error when the database doesn't exist
            if ($e->errorInfo[1] == 1049) {
                throw new Exception("The database doesn't exist", ApiResponse::INTERNAL_SERVER_ERROR);
            }

            throw new Exception("There was an error while trying to execute the query", ApiResponse::INTERNAL_SERVER_ERROR);
        }
    }
}
