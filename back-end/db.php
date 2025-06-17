<?php

class Database
{
    private $HOST = "db";
    private $USERNAME = "dev_user";
    private $PASSWORD = "dev_password";
    private $DBNAME = "test_db";

    public $connection;

    public function connect()
    {
        try {
            $DSN = "mysql:host={$this->HOST};dbname={$this->DBNAME};charset=utf8";

            $this->connection = new PDO($DSN, $this->USERNAME, $this->PASSWORD);
            $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        } catch (PDOException $e) {
            error_log("Connection Error: " . $e->getMessage(), 3, '../../logs/error.log');
            throw new Exception("Database connection failed: " . $e->getMessage());
        }

        return $this->connection;
    }
}