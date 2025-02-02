<?php
session_start();
require_once __DIR__ . "/../../vendor/autoload.php";
use App\classes\Event;

header("Content-Type: application/json");

// CSRF Validation
if ($_POST['csrf_token'] !== $_SESSION['csrf_token']) {
    echo json_encode(["success" => false, "message" => "Invalid CSRF token!"]);
    exit;
}

$event = new Event();

// Handle File Upload
if (isset($_FILES['photo']) && $_FILES['photo']['error'] == 0) {
    $photo = $_FILES['photo'];
    $photo_name = time() . '_' . uniqid() . '.' . pathinfo($photo['name'], PATHINFO_EXTENSION);
    $photo_tmp_name = $photo['tmp_name'];
    $photo_path = __DIR__ . "/../assets/img/event/" . $photo_name;

    if (move_uploaded_file($photo_tmp_name, $photo_path)) {
        $response = $event->update_event($_POST, $_FILES);
    } else {
        echo json_encode(["success" => false, "message" => "Failed to upload image."]);
        exit;
    }
} else {
    $response = $event->update_event($_POST, []);
}

echo json_encode($response);
exit;
