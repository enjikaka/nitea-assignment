<?php

require_once __DIR__ . '/../models/category.php';
require_once __DIR__ . '/../utils/sanitizer.php';

class CategoriesController
{
    private $category;

    public function __construct()
    {
        $this->category = new Category();
    }

    private function jsonResponse($data, $statusCode = 200): void
    {
        // Sanitize the data to prevent XSS attacks
        $sanitizedData = Sanitizer::sanitizeData($data);

        http_response_code($statusCode);
        header('Content-Type: application/json; charset=UTF-8');
        echo json_encode($sanitizedData, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    }

    public function getAllCategories(): void
    {
        try {
            $result = $this->category->getAllCategories();

            $this->jsonResponse([
                "status" => "success",
                "message" => "Successfully got all categories",
                "data" => $result
            ]);
        } catch (Exception $e) {
            $this->jsonResponse([
                "status" => "error",
                "message" => $e->getMessage()
            ], 500);
        }
    }

    public function getCategoryById($categoryId): void
    {
        try {
            $result = $this->category->getCategoryById($categoryId);

            if (!$result) {
                $this->jsonResponse([
                    "status" => "error",
                    "message" => "Category not found"
                ], 404);
                return;
            }

            $this->jsonResponse([
                "status" => "success",
                "message" => "Successfully retrieved category data",
                "data" => $result
            ]);
        } catch (Exception $e) {
            $this->jsonResponse([
                "status" => "error",
                "message" => $e->getMessage()
            ], 500);
        }
    }

    public function addCategory(): void
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

            if (!isset($inputData['name']) || empty($inputData['name'])) {
                $this->jsonResponse([
                    "status" => "error",
                    "message" => "Category name is required"
                ], 400);
                return;
            }

            $categoryId = $this->category->addCategory($inputData['name']);

            if (!$categoryId) {
                $this->jsonResponse([
                    "status" => "error",
                    "message" => "Failed to add category"
                ], 500);
                return;
            }

            $result = $this->category->getCategoryById($categoryId);

            $this->jsonResponse([
                "status" => "success",
                "message" => "Successfully added new category",
                "data" => $result
            ], 201);
        } catch (Exception $e) {
            $this->jsonResponse([
                "status" => "error",
                "message" => $e->getMessage()
            ], 500);
        }
    }
}