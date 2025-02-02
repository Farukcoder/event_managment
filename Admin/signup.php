<?php
require_once __DIR__ . "/../vendor/autoload.php";

use App\classes\Signup;

$signup = new Signup();

if (isset($_POST['login'])) {
    $signup_error = $signup->signUp($_POST);
}
session_start();
?>
<!doctype html>
<html lang="en" class="fullscreen-bg">

<head>
    <title>Event Management System | Sign Up</title>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
    <!-- VENDOR CSS -->
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/vendor/font-awesome/css/font-awesome.min.css">
    <link rel="stylesheet" href="assets/vendor/linearicons/style.css">
    <!-- MAIN CSS -->
    <link rel="stylesheet" href="assets/css/main.css">
    <!-- FOR DEMO PURPOSES ONLY. You should remove this in your project -->
    <link rel="stylesheet" href="assets/css/demo.css">
    <!-- GOOGLE FONTS -->
    <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700" rel="stylesheet">
    <!-- ICONS -->
    <link rel="apple-touch-icon" sizes="76x76" href="assets/img/apple-icon.png">
    <link rel="icon" type="image/png" sizes="96x96" href="assets/img/favicon.png">
    <style>
        .auth-box {
            height: 570px !important
        }
    </style>
</head>

<body>
    <!-- WRAPPER -->
    <div id="wrapper">
        <div class="vertical-align-wrap">
            <div class="vertical-align-middle">
                <div class="auth-box ">
                    <div class="left">
                        <div class="content">
                            <div class="header">
                                <!-- <div class="logo text-center"><img src="assets/img/logo-dark.png" alt="Klorofil Logo"></div> -->
                                <p class="lead">Sign up to your account</p>
                            </div>
                            <div id="alertMessage"></div>
                            <form class="form-auth-small" id="signup-form">
                                <div class="form-group">
                                    <label for="full_name" class="control-label sr-only">Full Name</label>
                                    <input type="text" class="form-control" name="full_name" id="full_name" placeholder="Full Name">
                                    <small id="fullNameError" class="text-danger"></small>
                                </div>

                                <div class="form-group">
                                    <label for="username" class="control-label sr-only">Username</label>
                                    <input type="text" class="form-control" name="username" id="username" placeholder="Username">
                                    <small id="usernameError" class="text-danger"></small>
                                </div>

                                <div class="form-group">
                                    <label for="email" class="control-label sr-only">Email</label>
                                    <input type="text" class="form-control" name="email" id="email" placeholder="Email">
                                    <small id="emailError" class="text-danger"></small>
                                </div>

                                <div class="form-group">
                                    <label for="phone" class="control-label sr-only">Phone</label>
                                    <input type="text" class="form-control" name="phone" id="phone" placeholder="Phone">
                                    <small id="phoneError" class="text-danger"></small>
                                </div>

                                <div class="form-group">
                                    <label for="password" class="control-label sr-only">Password</label>
                                    <input type="password" class="form-control" name="password" id="password" placeholder="Password">
                                    <small id="passwordError" class="text-danger"></small>
                                </div>

                                <div class="form-group">
                                    <label for="confirm_password" class="control-label sr-only">Confirm Password</label>
                                    <input type="password" class="form-control" name="confirm_password" id="confirm_password" placeholder="Confirm Password">
                                    <small id="confirmPasswordError" class="text-danger"></small>
                                </div>

                                <button type="submit" class="btn btn-primary btn-lg btn-block" name="login">Sign Up</button>
                                <a href="login.php">Log in</a>
                            </form>

                        </div>
                    </div>
                    <div class="right">
						<div class="overlay"></div>
						<div class="content text">
							<h1 class="heading">Event Management System</h1>
						</div>
					</div>
                    <div class="clearfix"></div>
                </div>
            </div>
        </div>
    </div>
    <!-- END WRAPPER -->
</body>
<script src="assets/vendor/jquery/jquery.min.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {

        // Full Name Validation
        document.getElementById("full_name").addEventListener("input", function() {
            let fullName = this.value.trim();
            let fullNameError = document.getElementById("fullNameError");

            if (!/^[a-zA-Z\s]+$/.test(fullName)) {
                fullNameError.textContent = "Full name can only contain letters and spaces!";
                this.classList.add("is-invalid");
            } else {
                fullNameError.textContent = "";
                this.classList.remove("is-invalid");
                this.classList.add("is-valid");
            }
        });

        // Username Validation
        document.getElementById("username").addEventListener("input", function() {
            let username = this.value.trim();
            let usernameError = document.getElementById("usernameError");

            if (!/^[a-zA-Z0-9_]+$/.test(username)) {
                usernameError.textContent = "Username can only contain letters, numbers, and underscores!";
                this.classList.add("is-invalid");
            } else {
                usernameError.textContent = "";
                this.classList.remove("is-invalid");
                this.classList.add("is-valid");
            }
        });

        // Email Validation
        document.getElementById("email").addEventListener("input", function() {
            let email = this.value.trim();
            let emailError = document.getElementById("emailError");

            let emailRegex = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;

            if (!emailRegex.test(email)) {
                emailError.textContent = "Invalid email address!";
                this.classList.add("is-invalid");
            } else {
                emailError.textContent = "";
                this.classList.remove("is-invalid");
                this.classList.add("is-valid");
            }
        });

        // Bangladeshi Phone Number Validation
        document.getElementById("phone").addEventListener("input", function() {
            let phone = this.value.trim();
            let phoneError = document.getElementById("phoneError");

            let bdPhoneRegex = /^(?:\+8801[3-9]\d{8}|01[3-9]\d{8})$/;

            if (!bdPhoneRegex.test(phone)) {
                phoneError.textContent = "Invalid Bangladeshi phone number!";
                this.classList.add("is-invalid");
            } else {
                phoneError.textContent = "";
                this.classList.remove("is-invalid");
                this.classList.add("is-valid");
            }
        });

        // Password Validation
        document.getElementById("password").addEventListener("input", function() {
            let password = this.value.trim();
            let passwordError = document.getElementById("passwordError");

            if (password.length < 6) {
                passwordError.textContent = "Password must be at least 6 characters!";
                this.classList.add("is-invalid");
            } else {
                passwordError.textContent = "";
                this.classList.remove("is-invalid");
                this.classList.add("is-valid");
            }
        });

        // Confirm Password Validation
        document.getElementById("confirm_password").addEventListener("input", function() {
            let confirmPassword = this.value.trim();
            let password = document.getElementById("password").value.trim();
            let confirmPasswordError = document.getElementById("confirmPasswordError");

            if (confirmPassword !== password) {
                confirmPasswordError.textContent = "Passwords do not match!";
                this.classList.add("is-invalid");
            } else {
                confirmPasswordError.textContent = "";
                this.classList.remove("is-invalid");
                this.classList.add("is-valid");
            }
        });

    });

    $(document).ready(function() {
        $("#signup-form").on("submit", function(e) {
            e.preventDefault();
            let formData = $(this).serialize();

            $.ajax({
                url: "signup_ajax.php",
                type: "POST",
                data: formData,
                dataType: "json",
                success: function(response) {
                    $("#alertMessage").html("");
                    $(".text-danger").text("");

                    if (response.success) {
                        $("#alertMessage").html('<div class="alert alert-success">' + response.message + '</div>');
                        $("#signup-form")[0].reset();
                    } else {
                        if (response.errors) {
                            $("#fullNameError").text(response.errors.full_name || "");
                            $("#usernameError").text(response.errors.username || "");
                            $("#emailError").text(response.errors.email || "");
                            $("#phoneError").text(response.errors.phone || "");
                            $("#passwordError").text(response.errors.password || "");
                            $("#confirmPasswordError").text(response.errors.confirm_password || "");
                        }
                        if (response.message) {
                            $("#alertMessage").html('<div class="alert alert-danger">' + response.message + '</div>');
                        }
                    }
                    setTimeout(() => { $("#alertMessage").fadeOut(); }, 3000);
                },
                error: function() {
                    $("#alertMessage").html('<div class="alert alert-danger">An error occurred.</div>');
                }
            });
        });
    });
</script>

</html>