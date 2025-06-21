<?php

require_once __DIR__ . '/../models/product.php';

class ProductsController
{
    private $product;

    public function __construct()
    {
        $this->product = new Product();
    }

    private function jsonResponse($data, $statusCode = 200): void
    {
        http_response_code($statusCode);
        header('Content-Type: application/json; charset=UTF-8');
        echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    }

    public function getProductsSchema(): void
    {
        $this->jsonResponse($this->product->getSchema());
    }

    public function getAllProducts(): void
    {
        try {
            $result = $this->product->getAllProducts();

            $this->jsonResponse([
                "status" => "success",
                "message" => "Successfully got all products",
                "data" => $result
            ]);
        } catch (Exception $e) {
            $this->jsonResponse([
                "status" => "error",
                "message" => $e->getMessage()
            ], 500);
        }
    }

    public function getProductById($productId): void
    {
        try {
            $result = $this->product->getProductById($productId);

            $message = empty($result) ? "Product not found" : "Successfully retrieved product data";
            $statusCode = empty($result) ? 404 : 200;

            $this->jsonResponse([
                "status" => "success",
                "message" => $message,
                "data" => $result[0]
            ], $statusCode);
        } catch (Exception $e) {
            $this->jsonResponse([
                "status" => "error",
                "message" => $e->getMessage()
            ], 500);
        }
    }

    public function addNewProduct(): void
    {
        try {
            $inputData = json_decode(file_get_contents('php://input'), true);

            if ($inputData === null) {
                $this->jsonResponse([
                    "status" => "error",
                    "message" => "Data input is empty or invalid JSON"
                ], 400);
                return;
            }

            // Validate required fields
            if (!isset($inputData['name']) || !isset($inputData['image']) || !isset($inputData['price']) || !isset($inputData['categories'])) {
                $this->jsonResponse([
                    "status" => "error",
                    "message" => "Missing required fields: name, image, price, and categories are required"
                ], 400);
                return;
            }

            $name = $inputData['name'];
            $image = $inputData['image'];
            $price = $inputData['price'];
            $categories = $inputData['categories'];

            // Additional validation for empty values
            if (empty($name) || empty($image) || empty($price) || empty($categories)) {
                $this->jsonResponse([
                    "status" => "error",
                    "message" => "All fields must not be empty"
                ], 400);
                return;
            }

            $this->product->addNewProduct($name, $image, $price, $categories);

            $result = $this->product->getProductByName($name);
            if (empty($result)) {
                $message = "Adding new product failed";
                $statusCode = 400;
            } else {
                $message = "Successfully added new product";
                $statusCode = 201;
            }

            $this->jsonResponse([
                "status" => "success",
                "message" => $message,
                "data" => $result
            ], $statusCode);
        } catch (Exception $e) {
            $this->jsonResponse([
                "status" => "error",
                "message" => $e->getMessage()
            ], 500);
        }
    }

    public function editProductData($productId): void
    {
        try {
            if (empty($this->product->getProductById($productId))) {
                $this->jsonResponse([
                    "status" => "success",
                    "message" => "Product not found"
                ], 404);
                exit;
            }

            $inputData = json_decode(file_get_contents('php://input'), true);

            if ($inputData === null) {
                $this->jsonResponse([
                    "status" => "error",
                    "message" => "Data input is empty"
                ], 400);
                exit;
            }

            if (!isset($inputData['name']) && !isset($inputData['price']) && !isset($inputData['categories']) && !isset($inputData['image'])) {
                $this->jsonResponse([
                    "status" => "error",
                    "message" => "required data missing"
                ], 422);
                exit;
            }

            if (isset($inputData["name"])) {
                $this->product->editProductName($productId, $inputData["name"]);
            }

            if (isset($inputData["price"])) {
                $this->product->editProductPrice($productId, $inputData["price"]);
            }

            if (isset($inputData["categories"])) {
                $this->product->editProductCategories($productId, $inputData["categories"]);
            }

            if (isset($inputData["image"])) {
                $this->product->editProductImage($productId, $inputData["image"]);
            }

            $result = $this->product->getProductById($productId);

            $this->jsonResponse([
                "status" => "success",
                "message" => "Successfully updated product data",
                "data" => $result
            ]);
        } catch (Exception $e) {
            $this->jsonResponse([
                "status" => "error",
                "message" => $e->getMessage()
            ], 500);
        }
    }

    public function deleteProductById($productId): void
    {
        try {
            if (empty($this->product->getProductById($productId))) {
                $this->jsonResponse([
                    "status" => "success",
                    "message" => "Product not found"
                ], 404);
                exit;
            }

            $this->product->deleteProductById($productId);

            $this->jsonResponse([
                "status" => "success",
                "message" => "Successfully deleted product"
            ]);
        } catch (Exception $e) {
            $this->jsonResponse([
                "status" => "error",
                "message" => $e->getMessage()
            ], 500);
        }
    }
}
