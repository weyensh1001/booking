<?php
require("config.php");

// Update the SQL query to retrieve ratings and genre information
$sql = "SELECT movie_ratings.*, tbl_movie.Genre 
        FROM movie_ratings 
        INNER JOIN tbl_movie ON movie_ratings.movie_id = tbl_movie.movie_id";

$result = mysqli_query($con, $sql);

$userRatings = [];

while ($row = mysqli_fetch_assoc($result)) {
    extract($row);
    $user_id = $row['user_id'];
    $movie_id = $row['movie_id'];
    $rating = $row['ratings'];
    $genre = $row['Genre'];

    // Store the data in the userRatings array
    $userRatings[$user_id][$movie_id] = [
        'ratings' => $rating,
        'Genre' => $genre
    ];
}

// Calculate similarity between two users using cosine similarity
function calculateSimilarity($user1Ratings, $user2Ratings)
{
    $dotProduct = 0;
    $magnitudeUser1 = 0;
    $magnitudeUser2 = 0;

    foreach ($user1Ratings as $movie => $data) {
        if (isset($user2Ratings[$movie])) {
            $rating1 = $data['ratings'];
            $rating2 = $user2Ratings[$movie]['ratings'];
            $genre1 = $data['Genre'];
            $genre2 = $user2Ratings[$movie]['Genre'];

            // Calculate the dot product considering both ratings and genre similarity
            $dotProduct += ($rating1 * $rating2) * genreSimilarity($genre1, $genre2);
        }
        $magnitudeUser1 += pow($data['ratings'], 2);
    }

    foreach ($user2Ratings as $movie => $data) {
        $magnitudeUser2 += pow($data['ratings'], 2);
    }

    if ($magnitudeUser1 == 0 || $magnitudeUser2 == 0) {
        return 0; // Avoid division by zero
    }

    return $dotProduct / (sqrt($magnitudeUser1) * sqrt($magnitudeUser2));
}

// Function to calculate genre similarity (you need to define this function)
function genreSimilarity($genre1, $genre2)
{
    $genres1 = explode(",", $genre1);
    $genres2 = explode(",", $genre2);
    
    // Calculate the intersection of genres
    $intersection = array_intersect($genres1, $genres2);
    
    // Calculate the union of genres
    $union = array_unique(array_merge($genres1, $genres2));
    
    // Calculate Jaccard similarity as the size of the intersection divided by the size of the union
    $similarity = count($intersection) / count($union);
    
    return $similarity;
}

// Recommend movies using user-based collaborative filtering
function recommendMovies($userRatings, $targetUser, $numRecommendations)
{
    if (isset($userRatings[$targetUser])) {
        $targetUserRatings = $userRatings[$targetUser];
        $similarities = [];

        foreach ($userRatings as $user => $ratings) {
            if ($user !== $targetUser) {
                $similarity = calculateSimilarity($targetUserRatings, $ratings);
                $similarities[$user] = $similarity;
            }
        }

        arsort($similarities); // Sort users by similarity in descending order

        $recommendedMovies = [];

        foreach ($similarities as $user => $similarity) {
            $userRating = $userRatings[$user];
            foreach ($userRating as $movie => $data) {
                $rating = $data['ratings'];
                $genre = $data['Genre'];
                if (!isset($targetUserRatings[$movie])) {
                    // You can use $rating and $genre for recommendation scoring
                    $recommendedMovies[$movie] = $rating * $similarity;
                }
            }
        }

        arsort($recommendedMovies); // Sort recommended movies by score in descending order

        return array_slice($recommendedMovies, 0, $numRecommendations, true);
    } else {
        return []; // User not found in ratings data
    }
}

// Number of recommended movies to display
$numRecommendations = 5;

// Target user for recommendations
if (session_id() == "") {
    session_start();
}
extract($_SESSION);

$sql = "SELECT name FROM tbl_registration where user_id = $user";
$user_result = mysqli_query($con, $sql);
$user_info = mysqli_fetch_assoc($user_result);

$targetUser = $user_info['name'];

// Get recommended movies
$recommendedMovies = recommendMovies($userRatings, $targetUser, $numRecommendations);
?>