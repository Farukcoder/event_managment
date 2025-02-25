<?php
require_once __DIR__ . "/../vendor/autoload.php";
use App\classes\Login;

$login = new Login();

if (isset($_POST['login'])) {
	$login_error = $login->loginCheck($_POST);
}
session_start();
?>
<!doctype html>
<html lang="en" class="fullscreen-bg">

<head>
	<title>Event Management System | Login</title>
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
								<div class="logo text-center"><img src="assets/img/logo-dark.png" alt="Klorofil Logo"></div>
								<p class="lead">Login to your account</p>
								<?php if (isset($_SESSION['success_message'])) : ?>
									<div class="alert alert-success" role="alert">
										<?= htmlspecialchars($_SESSION['success_message']); ?>
									</div>
									<?php unset($_SESSION['success_message']); ?>
								<?php endif; ?>

								<?php if (isset($_SESSION['error_message'])) : ?>
									<div class="alert alert-danger" role="alert">
										<?= htmlspecialchars($_SESSION['error_message']); ?>
									</div>
									<?php unset($_SESSION['error_message']); ?>
								<?php endif; ?>
							</div>
							<form class="form-auth-small" action="" method="post">
								<div class="form-group">
									<label for="username" class="control-label sr-only">Username Or Email</label>
									<input type="text" class="form-control" name="username" id=""
										placeholder="Username Or Email" value="faruk">
								</div>
								<div class="form-group">
									<label for="password" class="control-label sr-only">Password</label>
									<input type="password" class="form-control" id="" name="password"
										placeholder="Password" value="123456789">
								</div>
								<button type="submit" class="btn btn-primary btn-lg btn-block"
									name="login">Login</button>
									<a href="signup.php">Sign up</a>
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

</html>