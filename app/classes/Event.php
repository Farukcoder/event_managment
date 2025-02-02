<?php


namespace App\classes;

use App\classes\Database;

use mysqli;

class Event
{
    public function create_event($data, $file)
    {
        $conn = database::dbcon();

        // Validate required fields
        if (empty($data['title']) || empty($data['content']) || empty($data['total_members']) || empty($data['event_date'])) {
            return ["success" => false, "message" => "All fields are required."];
        }

        $title = trim($data['title']);
        $content = trim($data['content']);
        $user_id = $_SESSION['user_id'] ?? null; // Ensure user is logged in
        $total_members = intval($data['total_members']);
        $event_date = $data['event_date'];

        if (!$user_id) {
            return ["success" => false, "message" => "User not authenticated."];
        }

        // File upload handling
        if (!isset($file['photo']) || $file['photo']['error'] !== UPLOAD_ERR_OK) {
            return ["success" => false, "message" => "File upload error!"];
        }

        $file_info = pathinfo($file['photo']['name']);
        $file_ext = strtolower($file_info['extension']);
        $allowed_extensions = ['jpg', 'jpeg', 'png', 'gif'];

        if (!in_array($file_ext, $allowed_extensions)) {
            return ["success" => false, "message" => "Invalid file type!"];
        }

        $file_name = time() . '_' . uniqid() . '.' . $file_ext;
        $upload_path = __DIR__ . '/../../Admin/assets/img/event/' . $file_name;

        // Database query
        $query = "INSERT INTO `events` (`user_id`, `title`, `content`, `photo`, `total_members`, `event_date`) VALUES (?, ?, ?, ?, ?, ?)";

        if ($stmt = mysqli_prepare($conn, $query)) {
            mysqli_stmt_bind_param($stmt, "isssis", $user_id, $title, $content, $file_name, $total_members, $event_date);
            $result = mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);

            if ($result) {
                if (move_uploaded_file($file['photo']['tmp_name'], $upload_path)) {
                    return ["success" => true, "message" => "Event created successfully!", "file" => $file_name];
                } else {
                    return ["success" => false, "message" => "Event created but file upload failed!"];
                }
            } else {
                return ["success" => false, "message" => "Event creation failed!"];
            }
        } else {
            return ["success" => false, "message" => "Database error!"];
        }
    }

    // public function attendeeCreate($data)
    // {
    //     $conn = database::dbcon();

    //     // Sanitize & Validate Input
    //     $name = htmlspecialchars(trim($data['name']));
    //     $email = filter_var(trim($data['email']), FILTER_SANITIZE_EMAIL);
    //     $phone = trim($data['phone']);
    //     $event_id = intval($data['event_id']);

    //     // Validate if fields are empty
    //     if (empty($name) || empty($email) || empty($phone) || empty($event_id)) {
    //         return ["success" => false, "message" => "All fields are required."];
    //     }

    //     // Validate Email Format
    //     if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    //         return ["success" => false, "message" => "Invalid email format."];
    //     }

    //     // Validate Bangladeshi Phone Number
    //     $bdPhoneRegex = "/^(?:\+8801[3-9]\d{8}|01[3-9]\d{8})$/";
    //     if (!preg_match($bdPhoneRegex, $phone)) {
    //         return ["success" => false, "message" => "Invalid Bangladeshi phone number format."];
    //     }

    //     $user_name = $this->makeSlug($name);

    //     // Generate Password
    //     $password = $user_name . "_2025";
    //     $hashed_password = password_hash($password, PASSWORD_BCRYPT); // Hash for security

    //     // Check if user already exists using email
    //     $checkUserQuery = "SELECT id FROM users WHERE email = ?";
    //     $stmt = mysqli_prepare($conn, $checkUserQuery);
    //     mysqli_stmt_bind_param($stmt, "s", $email);
    //     mysqli_stmt_execute($stmt);
    //     $result = mysqli_stmt_get_result($stmt);

    //     if ($row = mysqli_fetch_assoc($result)) {
    //         $user_id = $row['id']; // Use existing user ID
    //     } else {
    //         // Insert New User
    //         $insertUserQuery = "INSERT INTO users (name, username, email, phone, password) VALUES (?, ?, ?, ?, ?)";
    //         $stmt = mysqli_prepare($conn, $insertUserQuery);
    //         mysqli_stmt_bind_param($stmt, "sssss", $name, $user_name, $email, $phone, $hashed_password);
    //         $insertResult = mysqli_stmt_execute($stmt);

    //         if (!$insertResult) {
    //             return ["success" => false, "message" => "Failed to register user."];
    //         }

    //         $user_id = mysqli_insert_id($conn); // Get new user ID
    //     }

    //     // **Check if user has already booked this event**
    //     $checkAttendeeQuery = "SELECT id FROM attendees WHERE event_id = ? AND user_id = ?";
    //     $stmt = mysqli_prepare($conn, $checkAttendeeQuery);
    //     mysqli_stmt_bind_param($stmt, "ii", $event_id, $user_id);
    //     mysqli_stmt_execute($stmt);
    //     $result = mysqli_stmt_get_result($stmt);

    //     if (mysqli_fetch_assoc($result)) {
    //         return ["success" => false, "message" => "You have already booked this event."];
    //     }

    //     // Insert into attendees table
    //     $insertAttendeeQuery = "INSERT INTO attendees (event_id, user_id) VALUES (?, ?)";
    //     $stmt = mysqli_prepare($conn, $insertAttendeeQuery);
    //     mysqli_stmt_bind_param($stmt, "ii", $event_id, $user_id);
    //     $result = mysqli_stmt_execute($stmt);

    //     if ($result) {
    //         return ["success" => true, "message" => "Your booking has been confirmed!"];
    //     } else {
    //         return ["success" => false, "message" => "Failed to book the event. Please try again."];
    //     }
    // }

    public function attendeeCreate($data)
    {
        $conn = database::dbcon();
    
        // Sanitize & Validate Input
        $name = htmlspecialchars(trim($data['name']));
        $email = filter_var(trim($data['email']), FILTER_SANITIZE_EMAIL);
        $phone = trim($data['phone']);
        $event_id = intval($data['event_id']);
    
        // Validate if fields are empty
        if (empty($name) || empty($email) || empty($phone) || empty($event_id)) {
            return ["success" => false, "message" => "All fields are required."];
        }
    
        // Validate Email Format
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return ["success" => false, "message" => "Invalid email format."];
        }
    
        // Validate Bangladeshi Phone Number
        $bdPhoneRegex = "/^(?:\+8801[3-9]\d{8}|01[3-9]\d{8})$/";
        if (!preg_match($bdPhoneRegex, $phone)) {
            return ["success" => false, "message" => "Invalid Bangladeshi phone number format."];
        }
    
        // Get Event Total Members Limit
        $eventQuery = "SELECT total_members FROM events WHERE id = ?";
        $stmt = mysqli_prepare($conn, $eventQuery);
        mysqli_stmt_bind_param($stmt, "i", $event_id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        
        if (!$event = mysqli_fetch_assoc($result)) {
            return ["success" => false, "message" => "Invalid event."];
        }
        
        $total_members = $event['total_members'];
    
        // Count Current Attendees for Event
        $countAttendeesQuery = "SELECT COUNT(id) as total FROM attendees WHERE event_id = ?";
        $stmt = mysqli_prepare($conn, $countAttendeesQuery);
        mysqli_stmt_bind_param($stmt, "i", $event_id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $attendeeData = mysqli_fetch_assoc($result);
        $current_attendees = $attendeeData['total'];
    
        // Check if event is already full
        if ($current_attendees >= $total_members) {
            return ["success" => false, "message" => "Booking full! No more slots available."];
        }
    
        $user_name = $this->makeSlug($name);
    
        // Generate Password
        $password = $user_name . "_2025";
        $hashed_password = password_hash($password, PASSWORD_BCRYPT);
    
        // Check if user already exists using email
        $checkUserQuery = "SELECT id FROM users WHERE email = ?";
        $stmt = mysqli_prepare($conn, $checkUserQuery);
        mysqli_stmt_bind_param($stmt, "s", $email);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
    
        if ($row = mysqli_fetch_assoc($result)) {
            $user_id = $row['id'];
        } else {
            // Insert New User
            $insertUserQuery = "INSERT INTO users (name, username, email, phone, password) VALUES (?, ?, ?, ?, ?)";
            $stmt = mysqli_prepare($conn, $insertUserQuery);
            mysqli_stmt_bind_param($stmt, "sssss", $name, $user_name, $email, $phone, $hashed_password);
            $insertResult = mysqli_stmt_execute($stmt);
    
            if (!$insertResult) {
                return ["success" => false, "message" => "Failed to register user."];
            }
    
            $user_id = mysqli_insert_id($conn);
        }
    
        // Check if user has already booked this event
        $checkAttendeeQuery = "SELECT id FROM attendees WHERE event_id = ? AND user_id = ?";
        $stmt = mysqli_prepare($conn, $checkAttendeeQuery);
        mysqli_stmt_bind_param($stmt, "ii", $event_id, $user_id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
    
        if (mysqli_fetch_assoc($result)) {
            return ["success" => false, "message" => "You have already booked this event."];
        }
    
        // Insert into attendees table
        $insertAttendeeQuery = "INSERT INTO attendees (event_id, user_id) VALUES (?, ?)";
        $stmt = mysqli_prepare($conn, $insertAttendeeQuery);
        mysqli_stmt_bind_param($stmt, "ii", $event_id, $user_id);
        $result = mysqli_stmt_execute($stmt);
    
        if ($result) {
            return ["success" => true, "message" => "Your booking has been confirmed!"];
        } else {
            return ["success" => false, "message" => "Failed to book the event. Please try again."];
        }
    }
    
    public function makeSlug($string)
    {
        // Convert to lowercase
        $string = strtolower(trim($string));

        // Replace spaces and special characters with a hyphen
        $string = preg_replace('/[^a-z0-9-]+/', '-', $string);

        // Remove multiple hyphens and trim
        $string = preg_replace('/-+/', '-', $string);

        return trim($string, '-');
    }

    // public function all_event()
    // {
    //     $userId = $_SESSION['user_id'];
    //     $query = "SELECT
    //         events.*,
    //         users.name AS user_name,
    //         COUNT(attendees.id) AS registered_members
    //       FROM events
    //       INNER JOIN users ON events.user_id = users.id
    //       LEFT JOIN attendees ON events.id = attendees.event_id
    //       WHERE events.user_id = '$userId'  -- Add this WHERE clause
    //       GROUP BY events.id
    //       ORDER BY events.title DESC";
    //
    //     $result = mysqli_query(database::dbcon(), $query);
    //     return $result;
    // }


    public function active($id)
    {
        $query = "UPDATE `events` SET status ='1'  WHERE id = '$id'";
        mysqli_query(database::dbcon(), $query);
    }

    public function inactive($id)
    {
        $query = "UPDATE `events` SET status ='0'  WHERE id = '$id'";
        mysqli_query(database::dbcon(), $query);
    }

    public function delete($id)
    {
        $conn = database::dbcon();

        $query = "DELETE FROM `events` WHERE id = ?";

        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, "i", $id);
        $success = mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);

        return $success;
    }


    public function all_active_event()
    {
        $conn = database::dbcon();

        $query = "SELECT events.*, 
                     users.name AS user_name, 
                     COUNT(attendees.id) AS registered_members 
              FROM events
              JOIN users ON events.user_id = users.id
              LEFT JOIN attendees ON events.id = attendees.event_id
              WHERE events.status = ?
              GROUP BY events.id
              ORDER BY events.id DESC";

        $stmt = mysqli_prepare($conn, $query);
        $status = 1;
        mysqli_stmt_bind_param($stmt, "i", $status);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        return $result;
    }



    public function single_event($id)
    {
        $dbcon = database::dbcon();

        $query = "SELECT events.*, 
                     users.name AS user_name, 
                     COUNT(attendees.id) AS registered_members 
              FROM events
              JOIN users ON events.user_id = users.id
              LEFT JOIN attendees ON events.id = attendees.event_id
              WHERE events.id = ?
              GROUP BY events.id";

        $stmt = mysqli_prepare($dbcon, $query);
        mysqli_stmt_bind_param($stmt, "i", $id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        mysqli_stmt_close($stmt);

        return $result;
    }


    public function select_row($id = '')
    {
        $query = "SELECT * FROM events WHERE id='$id'";
        $result = mysqli_query(database::dbcon(), $query);
        return $result;
    }

    public function update_event($data, $file)
    {
        $conn = database::dbcon();
    
        if (!isset($data['user_id'])) {
            return ["success" => false, "message" => "Invalid data provided."];
        }
    
        $user_id = intval($data['user_id']);
        $title = trim($data['title']);
        $content = trim($data['content']);
        $total_members = intval($data['total_members']);
        $event_date = date("Y-m-d H:i:s", strtotime($data['event_date']));
        $id = intval($data['id']);
    
        if (empty($title) || empty($content) || empty($total_members) || empty($event_date)) {
            return ["success" => false, "message" => "All fields are required."];
        }
    
        $photo_column = "";
        if (isset($file['photo']['name']) && $file['photo']['name'] != '') {
            $new_file_name = time() . '_' . uniqid() . '.' . pathinfo($file['photo']['name'], PATHINFO_EXTENSION);
            move_uploaded_file($file['photo']['tmp_name'], __DIR__ . "/../../Admin/assets/img/event/" . $new_file_name);
            $photo_column = ", `photo` = '$new_file_name'";
        }
    
        $query = "UPDATE `events` SET `user_id`=?, `title`=?, `content`=?, `total_members`=?, `event_date`=? $photo_column WHERE `id`=?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("issisi", $user_id, $title, $content, $total_members, $event_date, $id);
        $result = $stmt->execute();
    
        return $result ? ["success" => true, "message" => "Event updated successfully!"] : ["success" => false, "message" => "Event update failed!"];
    }
    
    



    public function totalEvent()
    {
        $conn = database::dbcon();
        $user_id = $_SESSION['user_id'];

        // Query to count total events for the user
        $query = "SELECT COUNT(*) AS total FROM events WHERE user_id=?";
        $stmt = mysqli_prepare($conn, $query);

        // Bind parameter
        mysqli_stmt_bind_param($stmt, "i", $user_id);

        // Execute the query
        mysqli_stmt_execute($stmt);

        // Get the result
        $result = mysqli_stmt_get_result($stmt);
        $row = mysqli_fetch_assoc($result);

        // Close the statement
        mysqli_stmt_close($stmt);

        // Return the total count
        return $row['total'];
    }

    public function getTotalAttendeesByEvent($event_id)
    {
        $conn = database::dbcon();

        // Query to count total attendees for the specific event_id
        $query = "SELECT COUNT(*) AS total FROM attendees WHERE event_id=?";
        $stmt = mysqli_prepare($conn, $query);

        // Bind parameter
        mysqli_stmt_bind_param($stmt, "i", $event_id);

        // Execute the query
        mysqli_stmt_execute($stmt);

        // Get the result
        $result = mysqli_stmt_get_result($stmt);
        $row = mysqli_fetch_assoc($result);

        // Close the statement
        mysqli_stmt_close($stmt);

        // Return the total count for that event
        return $row['total'];
    }


    public function getTotalAttendeesByUser()
    {
        $conn = database::dbcon();
        $user_id = $_SESSION['user_id'];

        // Query to get all event_ids for the user
        $query = "SELECT id FROM events WHERE user_id=?";
        $stmt = mysqli_prepare($conn, $query);

        // Bind parameter
        mysqli_stmt_bind_param($stmt, "i", $user_id);

        // Execute the query
        mysqli_stmt_execute($stmt);

        // Get the result
        $result = mysqli_stmt_get_result($stmt);
        $total_attendees = 0;

        // Fetch all event_ids and sum attendees for each event
        while ($row = mysqli_fetch_assoc($result)) {
            $event_id = $row['id'];

            // Get the total attendees for the event
            $attendees_count = $this->getTotalAttendeesByEvent($event_id);

            // Add the event's attendees count to the total
            $total_attendees += $attendees_count;
        }

        // Close the statement
        mysqli_stmt_close($stmt);

        // Return the total count of attendees
        return $total_attendees;
    }
}
