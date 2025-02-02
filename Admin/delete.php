<?php
require_once __DIR__ . "/../vendor/autoload.php";

use App\classes\Event;

$event = new Event();

// event delete
if (isset($_GET['events'])) {
    $id = $_GET['id'];
    $event->delete($id);
    $file = $_GET['filename'];
    unlink('assets/img/event/' . $file);
    header('location:manage_event.php');
}
