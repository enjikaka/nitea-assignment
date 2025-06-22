<?php

require_once __DIR__ . '/../db.php';

class Category
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

    public function getAllCategories(): ?array
    {
        try {
            $query = "SELECT * FROM categories ORDER BY name";

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

    public function getCategoryById($id): ?array
    {
        try {
            $query = "SELECT * FROM categories WHERE id = :id";

            if ($this->conn === null) {
                throw new Exception("Database connection is null.");
            }

            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            $this->logError("Query failed: " . $e->getMessage());
            return null;
        }
    }

    public function getCategoryByName($name): ?array
    {
        try {
            $query = "SELECT * FROM categories WHERE name = :name";

            if ($this->conn === null) {
                throw new Exception("Database connection is null.");
            }

            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':name', $name, PDO::PARAM_STR);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            $this->logError("Query failed: " . $e->getMessage());
            return null;
        }
    }

    public function addCategory($name): ?int
    {
        try {
            $query = "INSERT INTO categories (name) VALUES (:name)";

            if ($this->conn === null) {
                throw new Exception("Database connection is null.");
            }

            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':name', $name, PDO::PARAM_STR);
            $stmt->execute();

            return $this->conn->lastInsertId();
        } catch (PDOException $e) {
            $this->logError("Query failed: " . $e->getMessage());
            return null;
        }
    }

    public function getOrCreateCategory($name): int
    {
        $existingCategory = $this->getCategoryByName($name);
        if ($existingCategory) {
            return $existingCategory['id'];
        }

        $newId = $this->addCategory($name);
        return $newId ?: 0;
    }

    public function getProductCategories($productId): ?array
    {
        try {
            $query = "SELECT c.* FROM categories c 
                     INNER JOIN product_categories pc ON c.id = pc.category_id 
                     WHERE pc.product_id = :product_id 
                     ORDER BY c.name";

            if ($this->conn === null) {
                throw new Exception("Database connection is null.");
            }

            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':product_id', $productId, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            $this->logError("Query failed: " . $e->getMessage());
            return null;
        }
    }

    public function setProductCategories($productId, $categoryNames): void
    {
        try {
            if ($this->conn === null) {
                throw new Exception("Database connection is null.");
            }

            // Start transaction
            $this->conn->beginTransaction();

            // Remove existing category associations
            $deleteQuery = "DELETE FROM product_categories WHERE product_id = :product_id";
            $deleteStmt = $this->conn->prepare($deleteQuery);
            $deleteStmt->bindParam(':product_id', $productId, PDO::PARAM_INT);
            $deleteStmt->execute();

            // Add new category associations
            if (!empty($categoryNames)) {
                $insertQuery = "INSERT INTO product_categories (product_id, category_id) VALUES (:product_id, :category_id)";
                $insertStmt = $this->conn->prepare($insertQuery);

                foreach ($categoryNames as $categoryName) {
                    $categoryName = trim($categoryName);
                    if (!empty($categoryName)) {
                        $categoryId = $this->getOrCreateCategory($categoryName);
                        if ($categoryId > 0) {
                            $insertStmt->bindParam(':product_id', $productId, PDO::PARAM_INT);
                            $insertStmt->bindParam(':category_id', $categoryId, PDO::PARAM_INT);
                            $insertStmt->execute();
                        }
                    }
                }
            }

            // Commit transaction
            $this->conn->commit();
        } catch (PDOException $e) {
            // Rollback transaction on error
            if ($this->conn->inTransaction()) {
                $this->conn->rollBack();
            }
            $this->logError("Query failed: " . $e->getMessage());
        }
    }
}