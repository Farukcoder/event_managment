<?php
require_once __DIR__ . "/../vendor/autoload.php";

use App\classes\Event;

$event = new Event();

if (isset($_GET['active']) && isset($_GET['events'])) {

	$id = $_GET['id'];
	$event->active($id);
	header('location:manage_event.php');
}

if (isset($_GET['inactive']) && isset($_GET['events'])) {

	$id = $_GET['id'];
	$event->inactive($id);
	header('location:manage_event.php');
}
