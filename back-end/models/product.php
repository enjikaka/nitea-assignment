<?php

require_once __DIR__ . '/../db.php';
require_once __DIR__ . '/category.php';

class Product
{
    private $conn;
    private $category;
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
            $this->category = new Category();
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
                    "type" => "number",
                    "description" => "The price of the product"
                ],
                "categories" => [
                    "type" => "array",
                    "items" => [
                        "type" => "string"
                    ],
                    "description" => "Array of category names the product belongs to"
                ]
            ]
        ];
    }

    public function getAllProducts(): ?array
    {
        try {
            $query = "SELECT * FROM products ORDER BY name";

            if ($this->conn === null) {
                throw new Exception("Database connection is null.");
            }

            $stmt = $this->conn->query($query);
            $products = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Add categories to each product
            foreach ($products as &$product) {
                $product['categories'] = $this->category->getProductCategories($product['id']);
                // Convert categories array to array of names for API consistency
                $product['categories'] = array_map(function ($cat) {
                    return $cat['name'];
                }, $product['categories'] ?: []);
            }

            return $products;
        } catch (PDOException $e) {
            $this->logError("Query failed: " . $e->getMessage());
            return null;
        }
    }

    public function getProductById($id): ?array
    {
        try {
            $query = "SELECT * FROM products WHERE id = :id";

            if ($this->conn === null) {
                throw new Exception("Database connection is null.");
            }

            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            $product = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($product) {
                // Add categories to the product
                $categories = $this->category->getProductCategories($id);
                $product['categories'] = array_map(function ($cat) {
                    return $cat['name'];
                }, $categories ?: []);
            }

            return $product;
        } catch (PDOException $e) {
            $this->logError("Query failed: " . $e->getMessage());
            return null;
        }
    }

    public function getProductByName($name): ?array
    {
        try {
            $query = "SELECT * FROM products WHERE name = :name";

            if ($this->conn === null) {
                throw new Exception("Database connection is null.");
            }

            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(":name", $name, PDO::PARAM_STR);
            $stmt->execute();
            $product = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($product) {
                // Add categories to the product
                $categories = $this->category->getProductCategories($product['id']);
                $product['categories'] = array_map(function ($cat) {
                    return $cat['name'];
                }, $categories ?: []);
            }

            return $product;
        } catch (PDOException $e) {
            $this->logError("Query failed: " . $e->getMessage());
            return null;
        }
    }

    public function addNewProduct($name, $image, $price, $categories): ?int
    {
        try {
            $query = "INSERT INTO products (name, image, price) VALUES (?, ?, ?)";

            if ($this->conn === null) {
                throw new Exception("Database connection is null.");
            }

            $price = (float) $price;

            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(1, $name, PDO::PARAM_STR);
            $stmt->bindParam(2, $image, PDO::PARAM_STR);
            $stmt->bindParam(3, $price);
            $stmt->execute();

            $productId = $this->conn->lastInsertId();

            // Add categories
            if (!empty($categories)) {
                $categoryNames = is_array($categories) ? $categories : explode(',', $categories);
                $this->category->setProductCategories($productId, $categoryNames);
            }

            return $productId;
        } catch (PDOException $e) {
            $this->logError("Query failed: " . $e->getMessage());
            return null;
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

            $newValue = (float) $newValue;
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(1, $newValue);
            $stmt->bindParam(2, $id, PDO::PARAM_INT);
            $stmt->execute();
        } catch (PDOException $e) {
            $this->logError("Query failed: " . $e->getMessage());
        }
    }

    public function editProductImage($id, $newValue): void
    {
        try {
            $query = "UPDATE products SET image = ? WHERE id = ?";

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
            if ($this->conn === null) {
                throw new Exception("Database connection is null.");
            }

            $categoryNames = is_array($newValue) ? $newValue : explode(',', $newValue);
            $this->category->setProductCategories($id, $categoryNames);
        } catch (Exception $e) {
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
