<?php
session_start();
require_once __DIR__ . "/../vendor/autoload.php";
use App\classes\Signup;

header("Content-Type: application/json");

$response = ["success" => false, "errors" => []];

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $signup = new Signup();
    $response = $signup->signUp($_POST);
} else {
    $response["message"] = "Invalid request";
}

echo json_encode($response);
?>
