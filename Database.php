<?php
class Database
{
    private $connection;

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
    protected function setConnection(array $config)
    {
        // Set the DSN (Data Source Name) for the database.
        $dsn = "{$config['driver']}:
            host={$config['host']};
            port={$config['port']};
            dbname={$config['dbname']}";

        // Set the user and password.
        $user = $config['user'];
        $password = $config['password'];

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
     * @return PDOStatement The result of the query.
     */
    public function query(string $query, array $parameters = []): PDOStatement
    {
        // Prepare the query for execution
        $statement = $this->connection->prepare($query);
        // Execute the query
        $statement->execute();
        // Return the result as a PDOStatement object
        return $statement;
    }
}
