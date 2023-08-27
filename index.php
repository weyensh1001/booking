<html>

<body>
	<?php
	include('header.php');
	?>

	<div class="content">
		<div class="wrap">
			<div class="content-top">
				<div class="listview_1_of_3 images_1_of_3">
					<h2 style="color:#555;">Movie Trailers</h2>
					<div class="middle-list">
						<?php
						$qry4 = mysqli_query($con, "SELECT * FROM tbl_movie ORDER BY rand() LIMIT 6");

						while ($nm = mysqli_fetch_array($qry4)) {
						?>

							<div class="listimg1">
								<a target="_blank" href="<?php echo $nm['video_url']; ?>"><img src="<?php echo $nm['image']; ?>" alt="" /></a>
								<a target="_blank" href="<?php echo $nm['video_url']; ?>" class="link" style="text-decoration:none; font-size:14px;"><?php echo $nm['movie_name']; ?></a>
							</div>
						<?php
						}
						?>
					</div>


				</div>
				<?php include('movie_sidebar.php'); ?>
			</div>
		</div>

	</div>