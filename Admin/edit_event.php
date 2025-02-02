<?php
session_start();
require_once "templete/head.php";
require_once "templete/header.php";
require_once "templete/leftmenu.php";
require_once __DIR__ . "/../vendor/autoload.php";

use App\classes\Event;

$event = new Event();

// Get the event ID from URL
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Fetch event details
$result = $event->select_row($id);
$row = mysqli_fetch_assoc($result);

// Check if form is submitted
if (isset($_POST['update_event'])) {
    // Check CSRF token validity
    if ($_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        $update_msg_err = "Invalid CSRF token!";
    } else {
        // Handle file upload
        if (isset($_FILES['photo']) && $_FILES['photo']['error'] == 0) {
            $photo = $_FILES['photo'];
            $photo_name = time() . '_' . uniqid() . '.' . pathinfo($photo['name'], PATHINFO_EXTENSION);
            $photo_tmp_name = $photo['tmp_name'];
            $photo_path = "assets/img/event/" . $photo_name;

            // Move the uploaded photo
            if (move_uploaded_file($photo_tmp_name, $photo_path)) {
                $response = $event->update_event($_POST, $_FILES); // Pass file data
            } else {
                $response = ["success" => false, "message" => "Failed to upload image."];
            }
        } else {
            // If no new photo, retain the old one
            $response = $event->update_event($_POST, []);
        }

        // Store response message
        if ($response['success']) {
            $update_msg = $response['message'];
        } else {
            $update_msg_err = $response['message'];
        }
    }
}

// Generate a new CSRF token for security
$_SESSION['csrf_token'] = bin2hex(random_bytes(32));
?>

<!-- MAIN -->
<div class="main">
    <div class="main-content">
        <div class="container-fluid">
            <h3 class="page-title">Edit Event</h3>
            <div class="row">
                <div class="col-md-12">
                    <form id="eventForm" method="POST" enctype="multipart/form-data">
                        <div class="panel panel-headline">
                            <div class="panel-body">
                                <!-- Success & Error Messages -->
                                <div id="alertMessage">
                                    <?php if (isset($update_msg)) { ?>
                                        <div class="alert alert-success"><?= $update_msg; ?></div>
                                    <?php } elseif (isset($update_msg_err)) { ?>
                                        <div class="alert alert-danger"><?= $update_msg_err; ?></div>
                                    <?php } ?>
                                </div>

                                <div class="panel-body">
                                    <div class="col-md-6">
                                        <!-- Image Preview Section -->
                                        <div class="form-group">
                                            <label>Current Event Photo</label>
                                            <div class="mt-2">
                                                <img id="preview" src="<?= !empty($row['photo']) ? "assets/img/event/".$row['photo'] : '#' ?>" 
                                                    alt="Image preview" class="img-thumbnail" 
                                                    width="150px" height="150px" 
                                                    style="<?= !empty($row['photo']) ? '' : 'display:none;' ?>">
                                            </div>
                                        </div>

                                        <!-- Upload Event Photo -->
                                        <div class="form-group">
                                            <label for="photo">Photo</label>
                                            <input type="file" class="form-control" id="photo" name="photo">
                                        </div>

                                        <!-- Event Title -->
                                        <div class="form-group">
                                            <label for="title">Title</label>
                                            <input type="text" class="form-control" id="title" name="title" 
                                                value="<?= htmlspecialchars($row['title']) ?>" required>
                                        </div>

                                        <!-- Event Date -->
                                        <div class="form-group">
                                            <label for="event_date">Date</label>
                                            <input type="date" class="form-control" id="event_date" name="event_date"
                                                value="<?= date("Y-m-d", strtotime($row['event_date'])) ?>" required>
                                        </div>

                                        <!-- Total Members -->
                                        <div class="form-group">
                                            <label for="total_members">Total Members</label>
                                            <input type="number" class="form-control" id="total_members" name="total_members" 
                                                value="<?= $row['total_members'] ?>" required>
                                        </div>

                                        <!-- Event Content -->
                                        <div class="form-group">
                                            <label for="content">Description</label>
                                            <textarea class="form-control" id="content" name="content" rows="4" required><?= htmlspecialchars($row['content']) ?></textarea>
                                        </div>

                                        <!-- Hidden Fields -->
                                        <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token']; ?>">
                                        <input type="hidden" name="user_id" value="<?= $_SESSION['user_id']; ?>">
                                        <input type="hidden" name="id" value="<?= $row['id']; ?>">

                                        <!-- Submit Button -->
                                        <div class="form-group text-center">
                                            <button type="submit" class="btn btn-primary btn-lg" name="update_event">Update Event</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- JavaScript for Image Preview -->
<script>
    $(document).ready(function() {
        $("#photo").on("change", function() {
            let reader = new FileReader();
            reader.onload = function(e) {
                $("#preview").attr("src", e.target.result).show();
            }
            reader.readAsDataURL(this.files[0]);
        });

        if ($("#alertMessage").text().trim() !== "") {
            $("#alertMessage").show();
        }
    });

    $(document).ready(function() {
    $("#eventForm").submit(function(e) {
        e.preventDefault(); // Prevent default form submission

        let formData = new FormData(this); // Capture form data including files

        $.ajax({
            url: "event/ajax_update_event.php", // URL of PHP script handling the request
            type: "POST",
            data: formData,
            contentType: false,
            processData: false,
            dataType: "json", // Expect JSON response
            beforeSend: function() {
                $("#alertMessage").html('<div class="alert alert-info">Updating event...</div>');
            },
            success: function(response) {
                if (response.success) {
                    $("#alertMessage").html('<div class="alert alert-success">' + response.message + '</div>');
                    setTimeout(() => location.reload(), 2000); // Reload after success
                } else {
                    $("#alertMessage").html('<div class="alert alert-danger">' + response.message + '</div>');
                }
            },
            error: function(xhr, status, error) {
                $("#alertMessage").html('<div class="alert alert-danger">An error occurred: ' + error + '</div>');
            }
        });
    });

    // Live Image Preview
    $("#photo").on("change", function() {
        let reader = new FileReader();
        reader.onload = function(e) {
            $("#preview").attr("src", e.target.result).show();
        };
        reader.readAsDataURL(this.files[0]);
    });
});

</script>

<?php
require_once "templete/foot.php";
require_once "templete/footer.php";
?>
