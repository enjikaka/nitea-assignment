<?php

// Allow from any origin
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PATCH, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
header("Content-Type: application/json; charset=UTF-8");

// Handle preflight OPTIONS request
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

require_once __DIR__ . '/../controllers/products.php';
require_once __DIR__ . '/../controllers/categories.php';

$productsController = new ProductsController();
$categoriesController = new CategoriesController();

$requestUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$requestMethod = $_SERVER['REQUEST_METHOD'];

function handleIndexRoute($requestMethod)
{
    switch ($requestMethod) {
        case "GET":
            echo "Welcome to the product API!";
            break;
        default:
            handleMethodNotAllowedRoute();
            break;
    }
}

function handleProductsSchemaRoute($requestMethod)
{
    global $productsController;
    switch ($requestMethod) {
        case "GET":
            $productsController->getProductsSchema();
            break;
        default:
            handleMethodNotAllowedRoute();
            break;
    }
}

function handleProductsRoute($requestMethod)
{
    global $productsController;
    switch ($requestMethod) {
        case "GET":
            $productsController->getAllProducts();
            break;
        case "POST":
            $productsController->addNewProduct();
            break;
        default:
            handleMethodNotAllowedRoute();
            break;
    }
}

function handleProductRoute($requestMethod, $productId)
{
    global $productsController;
    switch ($requestMethod) {
        case "GET":
            $productsController->getProductById($productId);
            break;
        case "PATCH":
            $productsController->editProductData($productId);
            break;
        case "DELETE":
            $productsController->deleteProductById($productId);
            break;
        default:
            handleMethodNotAllowedRoute();
            break;
    }
}

function handleCategoriesRoute($requestMethod)
{
    global $categoriesController;
    switch ($requestMethod) {
        case "GET":
            $categoriesController->getAllCategories();
            break;
        case "POST":
            $categoriesController->addCategory();
            break;
        default:
            handleMethodNotAllowedRoute();
            break;
    }
}

function handleCategoryRoute($requestMethod, $categoryId)
{
    global $categoriesController;
    switch ($requestMethod) {
        case "GET":
            $categoriesController->getCategoryById($categoryId);
            break;
        default:
            handleMethodNotAllowedRoute();
            break;
    }
}

function handleNotFoundRoute()
{
    http_response_code(404);
    echo "Error 404! No route found!";
}

function handleMethodNotAllowedRoute()
{
    http_response_code(405);
    echo json_encode(["message" => "Method Not Allowed"]);
}


switch (true) {
    case $requestUri == "/":
        handleIndexRoute($requestMethod);
        break;
    case $requestUri == "/products.schema.json":
        handleProductsSchemaRoute($requestMethod);
        break;
    case $requestUri == "/products":
        handleProductsRoute($requestMethod);
        break;
    case preg_match('#^/products/(\d+)$#', $requestUri, $matches):
        handleProductRoute($requestMethod, $matches[1]);
        break;
    case $requestUri == "/categories":
        handleCategoriesRoute($requestMethod);
        break;
    case preg_match('#^/categories/(\d+)$#', $requestUri, $matches):
        handleCategoryRoute($requestMethod, $matches[1]);
        break;
    default:
        handleNotFoundRoute();
        break;
}
