<?php
namespace App\classes;

use App\classes\Database;

class Signup {
    public function signUp($data){
        $conn = Database::dbcon();
        $response = ["success" => false, "errors" => []];

        if ($conn->connect_error) {
            $response["message"] = "Database connection failed!";
            return $response;
        }

        // Secure input function
        function clean_input($data) {
            return htmlspecialchars(strip_tags(trim($data)));
        }

        // Input sanitization
        $full_name = clean_input($data['full_name']);
        $username = clean_input($data['username']);
        $email = clean_input($data['email']);
        $phone = clean_input($data['phone']);
        $password = $data['password'];
        $confirm_password = $data['confirm_password'];

        // Validation rules
        if (empty($full_name) || !preg_match("/^[a-zA-Z\s]+$/", $full_name)) {
            $response["errors"]["full_name"] = "Full name can only contain letters and spaces!";
        }

        if (empty($username) || !preg_match("/^[a-zA-Z0-9_]+$/", $username)) {
            $response["errors"]["username"] = "Username can only contain letters, numbers, and underscores!";
        } else {
            $stmt = $conn->prepare("SELECT id FROM users WHERE username = ?");
            $stmt->bind_param("s", $username);
            $stmt->execute();
            if ($stmt->get_result()->num_rows > 0) {
                $response["errors"]["username"] = "Username already taken!";
            }
            $stmt->close();
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $response["errors"]["email"] = "Invalid email address!";
        } else {
            $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
            $stmt->bind_param("s", $email);
            $stmt->execute();
            if ($stmt->get_result()->num_rows > 0) {
                $response["errors"]["email"] = "Email already registered!";
            }
            $stmt->close();
        }

        if (!preg_match("/^(?:\+8801[3-9]\d{8}|01[3-9]\d{8})$/", $phone)) {
            $response["errors"]["phone"] = "Invalid Bangladeshi phone number!";
        }

        if (strlen($password) < 6) {
            $response["errors"]["password"] = "Password must be at least 6 characters!";
        }

        if ($password !== $confirm_password) {
            $response["errors"]["confirm_password"] = "Passwords do not match!";
        }

        // If no errors, insert into database
        if (empty($response["errors"])) {
            $hashed_password = password_hash($password, PASSWORD_BCRYPT);
            $user_type = 1;

            $stmt = $conn->prepare("INSERT INTO users (name, username, email, phone, password, type) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("sssssi", $full_name, $username, $email, $phone, $hashed_password, $user_type);

            if ($stmt->execute()) {
                $response = ["success" => true, "message" => "Registration successful!"];
            } else {
                $response["message"] = "Registration failed!";
            }

            $stmt->close();
        }

        $conn->close();
        return $response;
    }
}
?>
