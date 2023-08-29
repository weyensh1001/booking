<?php
require("config.php");

if (isset($_SESSION['user'])) {

    $loggedInUserId = $_SESSION['user'];
    // Assuming $loggedInUserId holds the ID of the logged-in user
    $userRatedGenres = [];
    $userRatedGenresQuery = "SELECT DISTINCT Genre FROM tbl_movie WHERE movie_id IN (SELECT movie_id FROM movie_ratings WHERE user_id = $loggedInUserId)";
    $result = mysqli_query($con, $userRatedGenresQuery);
    while ($row = mysqli_fetch_assoc($result)) {
        $userRatedGenres[] = $row['Genre'];
    }

    // Calculate Jaccard Similarity
    function calculateJaccardSimilarity($setA, $setB)
    {
        $intersection = array_intersect($setA, $setB);
        $union = array_unique(array_merge($setA, $setB));
        $similarity = count($intersection) / count($union);
        return $similarity;
    }

    // Step 3: Find Similar Movies and Calculate Similarity
    $recommendedMovies = [];
    foreach ($userRatedGenres as $userGenre) {
        $moviesOfGenreQuery = "SELECT movie_id, Genre FROM tbl_movie WHERE Genre = '$userGenre'";
        $result = mysqli_query($con, $moviesOfGenreQuery);

        while ($row = mysqli_fetch_assoc($result)) {
            $targetMovieId = $row['movie_id'];
            $targetGenres = explode(',', $row['Genre']); // Convert genre string to array

            // Calculate Jaccard similarity
            $similarity = calculateJaccardSimilarity($userRatedGenres, $targetGenres);

            // Store similarity score in an array for the target movie
            if (!isset($recommendedMovies[$targetMovieId])) {
                $recommendedMovies[$targetMovieId] = 0;
            }
            $recommendedMovies[$targetMovieId] += $similarity;
        }
    }

    // Sort recommended movies by similarity score
    arsort($recommendedMovies);

    // Step 4: Display Recommended Movies
    if (!empty($recommendedMovies)) {
        return $recommendedMovies;
    } else {
        return [];
    }
}
