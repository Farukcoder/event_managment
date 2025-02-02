<?php
require_once __DIR__ . "/vendor/autoload.php";
require_once 'header.php';

use App\classes\Event;


$event = new Event();

$activeEvents =  $event->all_active_event();
// print_r($blog_post);
// exit;
$get_id = $_GET['id'];

$single_event = $event->single_event($get_id);
$row = mysqli_fetch_assoc($single_event);
// print_r($row);
?>
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow border-0">
                <!-- Event Image -->
                <img class="card-img-top" src="Admin/assets/img/event/<?= htmlspecialchars($row['photo']) ?>" 
                     alt="Event Image" style="height: 350px; object-fit: cover;">

                <div class="card-body">
                    <h2 class="card-title text-primary font-weight-bold"><?= htmlspecialchars($row['title']) ?></h2>

                    <!-- Event Date & Members -->
                    <div class="d-flex justify-content-between mb-3">
                        <span class="badge badge-success px-3 py-2">
                            <i class="fas fa-users"></i> <?= $row['total_members'] ?> Members
                        </span>
                        <span class="badge badge-success px-3 py-2">
                            <i class="fas fa-users"></i> <?= $row['registered_members'] ?> Registered Members
                        </span>
                        <span class="badge badge-info px-3 py-2">
                            <i class="fas fa-calendar-alt"></i> <?= date("F d, Y", strtotime($row['event_date'])) ?>
                        </span>
                    </div>

                    <!-- Event Content -->
                    <p class="card-text text-muted">
                        <?= nl2br(htmlspecialchars($row['content'])) ?>
                    </p>
                </div>

                <!-- Event Footer -->
                <div class="card-footer bg-light text-muted text-center">
                    <small>
                        <i class="fas fa-clock"></i> Posted on <?= date("F d, Y", strtotime($row['created_time'])) ?>
                        by <a href="#" class="text-primary font-weight-bold"><?= htmlspecialchars($row['user_name']) ?></a>
                    </small>
                </div>
            </div>

            <!-- Booking Form -->
            <div class="card mt-4 shadow border-0">
                <div class="card-body">
                    <h4 class="text-center text-dark font-weight-bold">Book This Event</h4>

                    <!-- Success/Error Message -->
                    <div id="bookingMessage"></div>

                    <form id="bookingForm">
                        <input type="hidden" name="event_id" value="<?= $row['id'] ?>">

                        <div class="form-group">
                            <label for="name">Full Name</label>
                            <input type="text" class="form-control" id="name" name="name" placeholder="Enter your name" required>
                        </div>

                        <div class="form-group">
                            <label for="email">Email Address</label>
                            <input type="email" class="form-control" id="email" name="email" placeholder="Enter your email" required>
                            <small class="form-text text-muted">Example: user@example.com</small>
                            <span id="emailError" class="text-danger"></span>
                        </div>

                        <div class="form-group">
                            <label for="phone">Phone Number (Bangladesh)</label>
                            <input type="hidden" name="event_id" value="<?= $row['id'] ?>">
                            <input type="tel" class="form-control" id="phone" name="phone" placeholder="e.g. 017XXXXXXXX or +88017XXXXXXXX" required>
                            <small class="form-text text-muted">Format: 01XXXXXXXX (11 digits) or +8801XXXXXXXX</small>
                            <span id="phoneError" class="text-danger"></span>
                        </div>

                        <button type="submit" class="btn btn-primary btn-block">Book Now</button>
                    </form>
                </div>
            </div>

            <!-- Back Button -->
            <div class="text-center mt-4">
                <a href="index.php" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left"></i> Back to Events
                </a>
            </div>
        </div>
    </div>
</div>

<!-- AJAX for Booking Form -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function () {
        $("#bookingForm").on("submit", function (e) {
            e.preventDefault(); // Prevent form from submitting normally

            let formData = $(this).serialize();

            $.ajax({
                url: "book_event.php",
                type: "POST",
                data: formData,
                dataType: "json",
                success: function (response) {
                    if (response.success) {
                        $("#bookingMessage").html('<div class="alert alert-success">' + response.message + '</div>');
                        $("#bookingForm")[0].reset();
                    } else {
                        $("#bookingMessage").html('<div class="alert alert-danger">' + response.message + '</div>');
                    }
                    setTimeout(function () {
                        $("#bookingMessage").fadeOut();
                    }, 5000);
                },
                error: function () {
                    $("#bookingMessage").html('<div class="alert alert-danger">An error occurred. Please try again.</div>');
                }
            });
        });
    });

    document.getElementById("phone").addEventListener("input", function () {
        let phone = this.value.trim();
        let phoneError = document.getElementById("phoneError");

        // Regular Expression for Bangladeshi Phone Number Validation
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

    document.getElementById("email").addEventListener("input", function () {
        let email = this.value.trim();
        let emailError = document.getElementById("emailError");

        // Regular Expression for Email Validation
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
</script>
