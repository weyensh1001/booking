<?php
include('config.php');
session_start();
date_default_timezone_set('Asia/Kathmandu');
?>

<!DOCTYPE HTML>
<html>

<head>
	<title>Mero Cinema</title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<link rel="stylesheet" href="css/style.css" type="text/css" media="all" />
	<link rel="stylesheet" href="css/flexslider.css" type="text/css" media="all" />
	<link type="text/css" rel="stylesheet" href="http://www.dreamtemplate.com/dreamcodes/tabs/css/tsc_tabs.css" />
	<link rel="stylesheet" href="css/tsc_tabs.css" type="text/css" media="all" />
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
	<script src='js/jquery.color-RGBa-patch.js'></script>
	<script src='js/example.js'></script>
	<script defer src='js/rating.js'></script>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
	<script src="https://khalti.com/static/khalti-checkout.js"></script>
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" integrity="sha512-z3gLpd7yknf1YoNbCzqRKc4qyor8gaKU1qmn+CShxbuBusANI9QpRohGBreCFkKxLhei6S9CQXFEbbKuqLg0DA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>

<body>
	<div class="header">
		<div class="header-top">
			<div class="wrap">
				<div class="h-logo">
					<a href="index.php">
						<h3 style="color:gold; padding:30% 0 0 0;">Mero Cinema</h3>
					</a>
				</div>
				<div class="nav-wrap">
					<ul class="group" id="example-one">
						<li><a href="index.php">Home</a></li>
						<!-- <li><a href="movies_events.php">Movies</a></li> -->
						<li><?php if (isset($_SESSION['user'])) {
								$us = mysqli_query($con, "select * from tbl_registration where user_id='" . $_SESSION['user'] . "'");
								$user = mysqli_fetch_array($us); ?><a href="profile.php"><?php echo $user['name']; ?></a><a href="logout.php">Logout</a><?php } else { ?><a href="login.php">Login</a> <a href="registration.php">Register</a><?php } ?></li>
					</ul>
				</div>
				<div class="clear"></div>

			</div>

		</div>

		<script>
			function myFunction() {
				if ($('#hero-demo').val() == "") {
					alert("Please enter movie name...");
					return false;
				} else {
					return true;
				}
			}
		</script>