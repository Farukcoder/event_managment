<?php
session_start();
require_once __DIR__ . "/vendor/autoload.php";
use App\classes\Event;

header("Content-Type: application/json");

$response = ["success" => false, "message" => ""];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $event = new Event();
    $result = $event->attendeeCreate($_POST);

    echo json_encode($result);
    exit;
}

// Invalid request response
echo json_encode(["success" => false, "message" => "Invalid request."]);
exit;
