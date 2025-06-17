<?php

require_once __DIR__ . '/../models/product.php';

class ProductsController
{
    private $product;

    public function __construct()
    {
        $this->product = new Product();
    }

    public function getAllProducts(): void
    {
        try {
            $result = $this->product->getAllProducts();

            http_response_code(200);
            header('Content-Type: application/json');
            echo json_encode([
                "status" => "success",
                "message" => "Successfully got all products",
                "data" => $result
            ]);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode([
                "status" => "error",
                "message" => $e->getMessage()
            ]);
        }
    }

    public function getProductById($productId): void
    {
        try {
            $result = $this->product->getProductById($productId);

            $message = empty($result) ? "Product not found" : "Successfully retrieved product data";

            http_response_code(empty($result) ? 404 : 200);
            header('Content-Type: application/json');
            echo json_encode([
                "status" => "success",
                "message" => $message,
                "data" => $result
            ]);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode([
                "status" => "error",
                "message" => $e->getMessage()
            ]);
        }
    }

    public function addNewProduct(): void
    {
        try {
            $name = $_POST['name'];
            $image = $_POST['image'];
            $price = $_POST['price'];
            $categories = $_POST['categories'];

            $this->product->addNewProduct($name, $image, $price, $categories);

            $result = $this->product->getProductByName($name);
            if (empty($result)) {
                $message = "Adding new product failed";
            } else {
                $message = "Successfully added new product";
            }

            http_response_code(empty($result) ? 400 : 201);
            header('Content-Type: application/json');
            echo json_encode([
                "status" => "success",
                "message" => $message,
                "data" => $result
            ]);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode([
                "status" => "error",
                "message" => $e->getMessage()
            ]);
        }
    }

    public function editProductData($productId): void
    {
        try {
            if (empty($this->product->getProductById($productId))) {
                http_response_code(404);
                echo json_encode([
                    "status" => "success",
                    "message" => "Product not found"
                ]);
                exit;
            }


            $inputData = json_decode(file_get_contents('php://input'), true);

            if ($inputData === null) {
                http_response_code(400);
                echo json_encode([
                    "status" => "error",
                    "message" => "Data input is empty"
                ]);
                exit;
            }

            if (!isset($inputData['name']) && !isset($inputData['price']) && !isset($inputData['categories']) && !isset($inputData['image'])) {
                http_response_code(422);
                echo json_encode([
                    "status" => "error",
                    "message" => "required data missing"
                ]);
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

            http_response_code(200);
            header('Content-Type: application/json');
            echo json_encode([
                "status" => "success",
                "message" => "Successfully updated product data",
                "data" => $result
            ]);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode([
                "status" => "error",
                "message" => $e->getMessage()
            ]);
        }
    }

    public function deleteProductById($productId): void
    {
        try {
            if (empty($this->product->getProductById($productId))) {
                http_response_code(404);
                echo json_encode([
                    "status" => "success",
                    "message" => "Product not found"
                ]);
                exit;
            }

            $this->product->deleteProductById($productId);

            http_response_code(200);
            header('Content-Type: application/json');
            echo json_encode([
                "status" => "success",
                "message" => "Successfully deleted product"
            ]);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode([
                "status" => "error",
                "message" => $e->getMessage()
            ]);
        }
    }
}