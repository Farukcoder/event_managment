<?php
session_start(); // Start the session
require_once __DIR__ . "/../../vendor/autoload.php";
use App\classes\Database;

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

$conn = Database::dbcon();
$userId = $_SESSION['user_id']; // Get the logged-in user ID

// DataTables parameters
$draw = isset($_GET['draw']) ? intval($_GET['draw']) : 1;
$start = isset($_GET['start']) ? intval($_GET['start']) : 0;
$length = isset($_GET['length']) ? intval($_GET['length']) : 10;
$search_value = isset($_GET['search']['value']) ? trim($_GET['search']['value']) : '';
$order_column_index = isset($_GET['order'][0]['column']) ? intval($_GET['order'][0]['column']) : 0;
$order_dir = isset($_GET['order'][0]['dir']) ? $_GET['order'][0]['dir'] : 'asc';

// Define columns mapping (ensure correct column order)
$columns = ["events.id", "events.title", "events.photo", "events.event_date", "events.total_members", "registered_members", "events.status"];
$order_column = $columns[$order_column_index] ?? "events.id";

// Base query
$query = "SELECT 
            events.id,
            events.title,
            events.photo,
            events.event_date,
            events.total_members,
            events.status,
            users.name AS user_name, 
            COUNT(attendees.id) AS registered_members 
          FROM events
          INNER JOIN users ON events.user_id = users.id
          LEFT JOIN attendees ON events.id = attendees.event_id
          WHERE events.user_id = ?";

// Filtering (if search is applied)
if (!empty($search_value)) {
    $query .= " AND (events.title LIKE ? OR users.name LIKE ?)";
}

// Grouping & Sorting
$query .= " GROUP BY events.id ORDER BY $order_column $order_dir LIMIT ?, ?";

// Prepare statement
$stmt = mysqli_prepare($conn, $query);

if (!empty($search_value)) {
    $search_param = "%{$search_value}%";
    mysqli_stmt_bind_param($stmt, "issii", $userId, $search_param, $search_param, $start, $length);
} else {
    mysqli_stmt_bind_param($stmt, "iii", $userId, $start, $length);
}

mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

// Get total records (without filters)
$total_query = "SELECT COUNT(*) as total FROM events WHERE user_id = ?";
$stmt_total = mysqli_prepare($conn, $total_query);
mysqli_stmt_bind_param($stmt_total, "i", $userId);
mysqli_stmt_execute($stmt_total);
$total_result = mysqli_stmt_get_result($stmt_total);
$total_records = mysqli_fetch_assoc($total_result)['total'];

// Get filtered records count
$filtered_query = "SELECT COUNT(DISTINCT events.id) as total FROM events
                   INNER JOIN users ON events.user_id = users.id
                   LEFT JOIN attendees ON events.id = attendees.event_id
                   WHERE events.user_id = ?";

if (!empty($search_value)) {
    $filtered_query .= " AND (events.title LIKE ? OR users.name LIKE ?)";
}

$stmt_filtered = mysqli_prepare($conn, $filtered_query);

if (!empty($search_value)) {
    mysqli_stmt_bind_param($stmt_filtered, "iss", $userId, $search_param, $search_param);
} else {
    mysqli_stmt_bind_param($stmt_filtered, "i", $userId);
}

mysqli_stmt_execute($stmt_filtered);
$filtered_result = mysqli_stmt_get_result($stmt_filtered);
$filtered_records = mysqli_fetch_assoc($filtered_result)['total'];

// Fetch results
$events = [];
while ($row = mysqli_fetch_assoc($result)) {
    $events[] = $row;
}

// Prepare JSON response
$response = [
    "draw" => $draw,
    "recordsTotal" => $total_records,
    "recordsFiltered" => $filtered_records,
    "data" => $events
];

header('Content-Type: application/json');
echo json_encode($response);
?>
