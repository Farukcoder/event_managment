<?php
require_once __DIR__ . "/../vendor/autoload.php";
use App\classes\Database;

// Establish a database connection using your Database class
$conn = Database::dbcon();

// Get the event_id from the GET parameters and cast it to an integer for safety
$event_id = isset($_GET['event_id']) ? (int) $_GET['event_id'] : 0;

// Prepare the SQL query with JOINs to fetch the required data
$sql = "SELECT 
            events.title AS event_name, 
            users.name, 
            users.email, 
            users.phone 
        FROM attendees
        JOIN users ON attendees.user_id = users.id
        JOIN events ON attendees.event_id = events.id
        WHERE attendees.event_id = ?";

$stmt = $conn->prepare($sql);
if (!$stmt) {
    die("Prepare failed: " . $conn->error);
}
$stmt->bind_param("i", $event_id);
$stmt->execute();
$result = $stmt->get_result();

// Set headers to indicate that the response is a CSV file download
header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename=Event_Attendees.csv');

// Open the output stream
$output = fopen('php://output', 'w');

// Output the CSV column headings
fputcsv($output, ['SL', 'Event Name', 'User Name', 'Email', 'Phone']);

// Loop through the results and output each row in CSV format
$sl = 1;
while ($row = $result->fetch_assoc()) {
    fputcsv($output, [
        $sl++,
        $row['event_name'],
        $row['name'],
        $row['email'],
        $row['phone']
    ]);
}

fclose($output);
exit();
?>
