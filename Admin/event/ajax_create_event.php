<?php
session_start();
require_once __DIR__ . "/../../vendor/autoload.php";
use App\classes\Event;

header("Content-Type: application/json");

$response = ["success" => false, "message" => ""];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate CSRF Token
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        echo json_encode(["success" => false, "message" => "Invalid CSRF Token!"]);
        exit;
    }

    $event = new Event();

    // Pass both $_POST and $_FILES
    $result = $event->create_event($_POST, $_FILES);

    echo json_encode($result);
    exit;
}

echo json_encode(["success" => false, "message" => "Invalid request."]);
