<?php
require_once __DIR__ . "/../vendor/autoload.php";
use App\classes\Event;
use App\classes\Database;

$conn = database::dbcon();

// Get parameters sent by DataTables
$event_id = $_GET['event_id'];
$start = $_GET['start'];
$length = $_GET['length'];
$search_value = $_GET['search']['value'];

// Base query
$query = "SELECT users.name, users.email, users.phone FROM attendees
          JOIN users ON attendees.user_id = users.id
          WHERE attendees.event_id = ?";

// Search filtering
if (!empty($search_value)) {
    $query .= " AND (users.name LIKE ? OR users.email LIKE ? OR users.phone LIKE ?)";
}

$query_count = "SELECT COUNT(*) as total FROM attendees WHERE event_id = ?";

// Prepare statements
$stmt = mysqli_prepare($conn, $query);
if (!empty($search_value)) {
    $search_param = "%{$search_value}%";
    mysqli_stmt_bind_param($stmt, "isss", $event_id, $search_param, $search_param, $search_param);
} else {
    mysqli_stmt_bind_param($stmt, "i", $event_id);
}
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

// Get total records without filtering
$stmt_count = mysqli_prepare($conn, $query_count);
mysqli_stmt_bind_param($stmt_count, "i", $event_id);
mysqli_stmt_execute($stmt_count);
$count_result = mysqli_stmt_get_result($stmt_count);
$total_records = mysqli_fetch_assoc($count_result)['total'];

// Fetch results
$attendees = [];
while ($row = mysqli_fetch_assoc($result)) {
    $attendees[] = $row;
}

// Prepare response
$response = [
    "draw" => intval($_GET['draw']),
    "recordsTotal" => $total_records,
    "recordsFiltered" => count($attendees),
    "data" => $attendees
];

header('Content-Type: application/json');
echo json_encode($response);
?>
