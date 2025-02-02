<?php
require_once "templete/head.php";
require_once "templete/header.php";
require_once "templete/leftmenu.php";
require_once __DIR__ . "/../vendor/autoload.php";
?>

<!-- MAIN -->
<div class="main">
	<!-- MAIN CONTENT -->
	<div class="main-content">
		<div class="container-fluid">
			<h3 class="page-title">Create Event</h3>
			<div class="row">
				<div class="col-md-12">
					<form id="eventForm" enctype="multipart/form-data">
						<div class="panel panel-headline">
							<div class="panel-body">
								<!-- Success & Error Messages -->
								<div id="alertMessage" style="display: none;"></div>

								<div class="panel-body">
									<div class="col-md-6">
										<div class="form-group">
											<div class="mt-2">
												<img id="preview" src="#" alt="Image preview" class="img-thumbnail" width="150px" height="150px" style="display: none;">
											</div>
										</div>

										<!-- Upload Event Photo -->
										<div class="form-group">
											<label for="photo">Photo</label>
											<input type="file" class="form-control" id="photo" name="photo" required>
										</div>

										<!-- Event Title -->
										<div class="form-group">
											<label for="title">Title</label>
											<input type="text" class="form-control" id="title" name="title" placeholder="Enter event title" required>
										</div>

										<!-- Event Date -->
										<div class="form-group">
											<label for="event_date">Date</label>
											<input type="date" class="form-control" id="event_date" name="event_date" required>
										</div>

										<!-- Total Members -->
										<div class="form-group">
											<label for="total_members">Total Members</label>
											<input type="number" class="form-control" id="total_members" name="total_members" placeholder="Enter total members" required>
										</div>

										<!-- Event Content -->
										<div class="form-group">
											<label for="content">Description</label>
											<textarea class="form-control" placeholder="Describe the event details..." name="content" rows="4" required></textarea>
										</div>

										<!-- CSRF Token -->
										<input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token']; ?>">

										<!-- Submit Button -->
										<div class="form-group text-center">
											<button type="submit" class="btn btn-primary btn-lg">Submit</button>
										</div>

									</div>
								</div>
							</div>
						</div>
						</form>
				</div>
			</div>
			<!-- END OVERVIEW -->
		</div>
	</div>
</div>
<!-- END MAIN CONTENT -->
</div>
<!-- END MAIN -->

<script>
	$(document).ready(function() {
		$("#eventForm").on("submit", function(e) {
			e.preventDefault();

			let formData = new FormData(this); // Automatically collects files and inputs

			$.ajax({
				url: "event/ajax_create_event.php",
				type: "POST",
				data: formData,
				contentType: false, // Required for FormData
				processData: false, // Prevents jQuery from automatically transforming data
				dataType: "json",
				success: function(response) {
					if (response.success) {
						$("#alertMessage").html('<div class="alert alert-success">' + response.message + '</div>').show();
						$("#eventForm")[0].reset();
						$("#preview").hide();
					} else {
						$("#alertMessage").html('<div class="alert alert-danger">' + response.message + '</div>').show();
					}

					setTimeout(function() {
						$("#alertMessage").fadeOut();
					}, 3000);
				},
				error: function() {
					$("#alertMessage").html('<div class="alert alert-danger">An error occurred.</div>').show();
				}
			});
		});

		// Image Preview
		$("#photo").on("change", function() {
			let reader = new FileReader();
			reader.onload = function(e) {
				$("#preview").attr("src", e.target.result).show();
			}
			reader.readAsDataURL(this.files[0]);
		});
	});
</script>

<?php
require_once "templete/foot.php";
require_once "templete/footer.php";
?>