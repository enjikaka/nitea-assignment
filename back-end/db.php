<?php

class Database
{
    private static $instance = null;
    private static $connection = null;

    private $HOST = "db";
    private $USERNAME = "dev_user";
    private $PASSWORD = "dev_password";
    private $DBNAME = "test_db";

    private function __construct()
    {
    }

    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function connect()
    {
        if (self::$connection === null) {
            try {
                $DSN = "mysql:host={$this->HOST};dbname={$this->DBNAME};charset=utf8";

                self::$connection = new PDO($DSN, $this->USERNAME, $this->PASSWORD);
                self::$connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch (PDOException $e) {
                error_log("Connection Error: " . $e->getMessage(), 3, '../../logs/error.log');
                throw new Exception("Database connection failed: " . $e->getMessage());
            }
        }

        return self::$connection;
    }

    public function getConnection()
    {
        return $this->connect();
    }
}
