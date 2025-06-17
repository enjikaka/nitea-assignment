<?php

// Allow from any origin
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PATCH, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// Handle preflight OPTIONS request
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

require_once __DIR__ . '/../controllers/products.php';

$productsController = new ProductsController();

$requestUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$requestMethod = $_SERVER['REQUEST_METHOD'];


if ($requestUri == "/") {
    switch ($requestMethod) {
        case "GET":
            echo "Welcome to the product API!";
            break;
        default:
            sendResponse405();
            break;
    }
} elseif ($requestUri == "/products.schema.json") {
    switch ($requestMethod) {
        case "GET":
            $productsController->getProductsSchema();
            break;
        default:
            sendResponse405();
            break;
    }
} elseif ($requestUri == "/products") {
    switch ($requestMethod) {
        case "GET":
            $productsController->getAllProducts();
            break;
        case "POST":
            $productsController->addNewProduct();
            break;
        default:
            sendResponse405();
            break;
    }
} elseif (preg_match('#^/products/(\d+)$#', $requestUri, $matches)) {
    $productId = $matches[1];
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
            sendResponse405();
            break;
    }
} else {
    http_response_code(404);
    echo "Error 404! No route found!";
}


function sendResponse405()
{
    http_response_code(405);
    echo json_encode(["message" => "Method Not Allowed"]);
}