<?php

require_once __DIR__ . '/../db.php';

class Product
{
    private $conn;
    private $logFile = __DIR__ . '/../logs/error.log';

    private function logError($message)
    {
        $logMessage = "[" . date("Y-m-d H:i:s") . "] " . $message . PHP_EOL;

        if (!@error_log($logMessage, 3, $this->logFile)) {
            error_log($logMessage);
        }
    }

    public function __construct()
    {
        try {
            $this->conn = (new Database())->connect();
        } catch (Exception $e) {
            $this->conn = null;
            $this->logError("Connection failed: " . $e->getMessage());
        }
    }

    public function getSchema(): ?array
    {
        return [
            "type" => "object",
            "required" => ["name", "image", "price", "categories"],
            "properties" => [
                "name" => [
                    "type" => "string",
                    "description" => "The name of the product"
                ],
                "image" => [
                    "type" => "string",
                    "description" => "URL or path to the product image"
                ],
                "price" => [
                    "type" => "string",
                    "description" => "The price of the product"
                ],
                "categories" => [
                    "type" => "string",
                    "description" => "List of categories the product belongs to"
                ]
            ]
        ];
    }

    public function getAllProducts(): ?array
    {
        try {
            $query = "SELECT * FROM products";

            if ($this->conn === null) {
                throw new Exception("Database connection is null.");
            }

            $stmt = $this->conn->query($query);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            $this->logError("Query failed: " . $e->getMessage());
            return null;
        }
    }

    public function getProductById($id): ?array
    {
        try {
            $query = "SELECT * FROM products WHERE id=:id";

            if ($this->conn === null) {
                throw new Exception("Database connection is null.");
            }

            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            $this->logError("Query failed: " . $e->getMessage());
            return null;
        }
    }

    public function getProductByName($name): ?array
    {
        try {
            $query = "SELECT * FROM products WHERE name=:name";

            if ($this->conn === null) {
                throw new Exception("Database connection is null.");
            }

            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(":name", $name, PDO::PARAM_STR);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            $this->logError("Query failed: " . $e->getMessage());
            return null;
        }
    }

    public function addNewProduct($name, $image, $price, $categories): void
    {
        try {
            $query = "INSERT INTO products (name, image, price, categories) VALUES (?, ?, ?, ?)";

            if ($this->conn === null) {
                throw new Exception("Database connection is null.");
            }

            $price = (float)$price;

            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(1, $name, PDO::PARAM_STR);
            $stmt->bindParam(2, $image, PDO::PARAM_STR);
            $stmt->bindParam(3, $price);
            $stmt->bindParam(4, $categories, PDO::PARAM_STR);
            $stmt->execute();
        } catch (PDOException $e) {
            $this->logError("Query failed: " . $e->getMessage());
        }
    }

    public function editProductName($id, $newValue): void
    {
        try {
            $query = "UPDATE products SET name = ? WHERE id = ?";

            if ($this->conn === null) {
                throw new Exception("Database connection is null.");
            }

            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(1, $newValue, PDO::PARAM_STR);
            $stmt->bindParam(2, $id, PDO::PARAM_INT);
            $stmt->execute();
        } catch (PDOException $e) {
            $this->logError("Query failed: " . $e->getMessage());
        }
    }

    public function editProductPrice($id, $newValue): void
    {
        try {
            $query = "UPDATE products SET price = ? WHERE id = ?";

            if ($this->conn === null) {
                throw new Exception("Database connection is null.");
            }

            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(1, $newValue, PDO::PARAM_STR);
            $stmt->bindParam(2, $id, PDO::PARAM_INT);
            $stmt->execute();
        } catch (PDOException $e) {
            $this->logError("Query failed: " . $e->getMessage());
        }
    }

    public function editProductImage($id, $newValue): void
    {
        try {
            $query = "UPDATE products SET imageUrl = ? WHERE id = ?";

            if ($this->conn === null) {
                throw new Exception("Database connection is null.");
            }

            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(1, $newValue, PDO::PARAM_STR);
            $stmt->bindParam(2, $id, PDO::PARAM_INT);
            $stmt->execute();
        } catch (PDOException $e) {
            $this->logError("Query failed: " . $e->getMessage());
        }
    }

    public function editProductCategories($id, $newValue): void
    {
        try {
            $query = "UPDATE products SET categories = ? WHERE id = ?";

            if ($this->conn === null) {
                throw new Exception("Database connection is null.");
            }

            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(1, $newValue, PDO::PARAM_STR);
            $stmt->bindParam(2, $id, PDO::PARAM_INT);
            $stmt->execute();
        } catch (PDOException $e) {
            $this->logError("Query failed: " . $e->getMessage());
        }
    }

    public function deleteProductById($productId): void
    {
        try {
            $query = "DELETE FROM products WHERE id = ?";

            if ($this->conn === null) {
                throw new Exception("Database connection is null.");
            }

            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(1, $productId, PDO::PARAM_INT);
            $stmt->execute();
        } catch (PDOException $e) {
            $this->logError("Query failed: " . $e->getMessage());
        }
    }
}
