<?php
include('header.php');


extract($_GET);
extract($_SESSION);

$rating_idname = "rating";
$rating_title = "Rate This Movie";
$user_rating = 0;

if (isset($_GET['ratings'])) {

	$rating_idname = "";
	$rating_title = "You've Rated";

	$check_sql = "SELECT * FROM movie_ratings where `user_id` = $user AND `movie_id`=$movie_id";
	$result = mysqli_query($con, $check_sql);

	$user_rating = $ratings;

	if (mysqli_num_rows($result) == 0 && isset($_GET['ratings'])) {

		$sql1 = "INSERT INTO `movie_ratings`(`user_id`, `movie_id`, `ratings`) VALUES ('$user','$movie_id','$ratings')";

		$sql2 = "UPDATE tbl_movie SET ratings = (SELECT AVG(ratings) FROM movie_ratings where movie_id = $movie_id) where movie_id = $movie_id";

		mysqli_query($con, $sql1);
		mysqli_query($con, $sql2);

	}

	
}

$qry2 = mysqli_query($con, "select * from tbl_movie where movie_id='" . $_GET['movie_id'] . "'");
$movie = mysqli_fetch_array($qry2);
$genre = $movie['Genre'];
?>
<style>
	.movie-card {
		margin: 15px 0;
		width: 300px;
		background-color: #f5f5f5;
		border-radius: 10px;
		padding: 20px;
		box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
	}

	.movie-card img {
		width: 100%;
		border-radius: 8px;
	}

	.movie-title {
		margin-top: 10px;
		font-size: 20px;
		font-weight: bold;
	}

	.movie-description {
		margin-top: 10px;
		font-size: 14px;
	}

	.btn {
		display: inline-block;
		margin-top: 15px;
		padding: 10px 20px;
		background-color: #007bff;
		color: #fff;
		text-decoration: none;
		border-radius: 5px;
	}

	.btn:hover {
		background-color: #0056b3;
	}

	.recommendation {
		display: flex;
		flex-wrap: wrap;
		justify-content: space-between;
	}
</style>
<div class="content">
	<div class="wrap">
		<div class="content-top">
			<div class="section group">
				<div class="about span_1_of_2">
					<h3 style="color:#444; font-size:23px;" class="text-center">
						<?php echo $movie['movie_name']; ?>
					</h3>
					<div class="about-top">
						<div class="grid images_3_of_2">
							<img src="<?php echo $movie['image']; ?>" alt="" />
						</div>
						<div class="desc span_3_of_2">
							<p class="p-link" style="font-size:15px"><b>Cast : </b>
								<?php echo $movie['cast']; ?>
							</p>
							<p class="p-link" style="font-size:15px"><b>Release Date : </b>
								<?php echo date('d-M-Y', strtotime($movie['release_date'])); ?>
							</p>
							<p class="p-link" style="font-size:15px"><b>Genre : </b>
								<?php echo $movie['Genre']; ?>
							</p>
							<p style="font-size:15px">
								<?php echo $movie['desc']; ?>
							</p>
							<p class="p-link" style="font-size:15px">
								<b>
									<?php echo $movie['ratings']; ?>
								</b>
								<i class="fa-solid fa-star"></i>
							</p>
							<a href="<?php echo $movie['video_url']; ?>" target="_blank" class="watch_but" style="text-decoration:none;">Watch Trailer</a>

							<h5 class="fa-2x">
								<?= $rating_title ?>
							</h5>
							<div id="<?= $rating_idname ?>" class="rating ">
								<?php

								for ($i = 1; $i <= 5; $i++) {
									if ($i <= $user_rating) {
										echo "
														<i class='fa-solid fa-star' id='$i' data-pid=' $movie_id'></i>
													";
									} else {
										echo "
														<i class='fa-regular fa-star' id='$i' data-pid='$movie_id'></i>
													";
									}
								}

								?>
							</div>
						</div>
						<div class="clear"></div>
					</div>
					<?php $s = mysqli_query($con, "select DISTINCT theatre_id from tbl_shows where movie_id='" . $movie['movie_id'] . "'");
					if (mysqli_num_rows($s)) { ?>
						<table class="table table-hover table-bordered text-center">
							<h3 style="color:#444;" class="text-center">Available Shows</h3>

							<thead>
								<tr>
									<th class="text-center" style="font-size:16px;"><b>Theatre</b></th>
									<th class="text-center" style="font-size:16px;"><b>Show Timings</b></th>
								</tr>
							</thead>
							<?php



							while ($shw = mysqli_fetch_array($s)) {

								$t = mysqli_query($con, "select * from tbl_theatre where id='" . $shw['theatre_id'] . "'");
								$theatre = mysqli_fetch_array($t);
							?>


								<tbody>
									<tr>
										<td>
											<?php echo $theatre['name'] . ", " . $theatre['place']; ?>
										</td>
										<td>
											<?php $tr = mysqli_query($con, "select * from tbl_shows where movie_id='" . $movie['movie_id'] . "' and theatre_id='" . $shw['theatre_id'] . "'");
											while ($shh = mysqli_fetch_array($tr)) {
												$ttm = mysqli_query($con, "select  * from tbl_show_time where st_id='" . $shh['st_id'] . "'");
												$ttme = mysqli_fetch_array($ttm);

											?>

												<a href="check_login.php?show=<?php echo $shh['s_id']; ?>&movie=<?php echo $shh['movie_id']; ?>&theatre=<?php echo $shw['theatre_id']; ?>"><button class="btn btn-default">
														<?php echo date('h:i A', strtotime($ttme['start_time'])); ?>
													</button></a>
											<?php
											}
											?>
										</td>
									</tr>
								</tbody>
							<?php
							}
							?>
						</table>
					<?php
					} else {
					?>
						<h3 style="color:#444; font-size:23px;" class="text-center">Currently there are no any shows
							available!</h3>
						<p class="text-center">Please check back later!</p>
					<?php
					}
					?>

					<h3>Recommended for You</h3>
					<div class="recommendation">
						<?php
						// Sample user profile
						$userProfile = [
							'genre' => [$movie['Genre']],
						];

						include('recommendation.php');
						if (isset($recommendedMovies)) {
							if (is_array($recommendedMovies)) {
								foreach ($recommendedMovies as $movieId => $similarityScore) {
									$movieDetailsQuery = "SELECT * FROM tbl_movie WHERE movie_id = $movieId";
									$movieDetailsResult = mysqli_query($con, $movieDetailsQuery);
									$movieDetails = mysqli_fetch_assoc($movieDetailsResult);
									extract($movieDetails);
									echo "
									<div class='movie-card'>
									<img src='$image' alt='Movie Poster'>
									<h2 class='movie-title'>$movie_name</h2>
									<p class='movie-description'><b>Cast:</b> $cast</p>
									<p class='movie-description'><b>Description:</b> $desc</p>
									<p class='movie-description'><b>Release Date:</b> $release_date</p>
									<p class='p-link' style='font-size:15px'>
									<b>$ratings</b>
									<i class='fa-solid fa-star'></i>
									</p>
									<a href='about.php?movie_id=$movie_id' class='btn' >Watch now</a><br>
									</div>
									";
								}
							} else {
								echo "<p> You have not rated any movie yet!</p>";
							}
						} else {
							echo "<p>Please Login to view your recommendation</p>";
						}
						?>
					</div>
				</div>
				<?php include('movie_sidebar.php'); ?>
			</div>
			<div class="clear"></div>
		</div>
	</div>
</div>

<?php include('footer.php'); ?>