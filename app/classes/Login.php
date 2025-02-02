<?php

namespace App\classes;

use App\classes\Database;

class Login
{
    public function loginCheck($data)
    {
        session_start();

        $user_input = $data['username'];
        $password = $data['password'];

        $db = new Database();
        $link = $db->dbcon();

        // Fetch user details including 'type' column
        $sql = "SELECT id, username, email, name, password, type FROM users WHERE username = ? OR email = ?";

        $stmt = mysqli_prepare($link, $sql);
        mysqli_stmt_bind_param($stmt, "ss", $user_input, $user_input);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if ($row = mysqli_fetch_assoc($result)) {
            // Verify password
            if (password_verify($password, $row['password'])) {
                // Check if user type is 1 (admin)
                if ($row['type'] == 1) {
                    $_SESSION['user_id'] = $row['id'];
                    $_SESSION['username'] = $row['username'];
                    $_SESSION['email'] = $row['email'];
                    $_SESSION['name'] = $row['name'];
                    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));

                    header("Location: index.php");
                    exit();
                } else {
                    $_SESSION['error_message'] = "Access denied! You are not an admin.";
                    header("Location: login.php");
                    exit();
                }
            }
        }

        // If user not found or password incorrect
        $_SESSION['error_message'] = "Username, email, or password is invalid!";
        header("Location: login.php");
        exit();
    }
}
