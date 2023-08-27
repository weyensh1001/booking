<?php
session_start();
require_once './config.php';
$q_r_movie = "SELECT tbl_movie.movie_id as movie_id, tbl_movie.movie_name as movie_name , seat.id as seat_id , seat.name FROM reserved_seat inner join tbl_movie on reserved_seat.movie_id = tbl_movie.movie_id inner join seat on reserved_seat.seat_id = seat.id where user = " . $_SESSION['user'];
// echo $q_r_movie;
$result = mysqli_query($con, $q_r_movie);
// echo $con->error;
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reserved Movie</title>
</head>

<body>
    <h1>Reserved Seat</h1>
    <table>
        <tr>
            <th>
                Movie name
            </th>
            <th>
                Seat name
            </th>
        </tr>
        <?php
        while ($reserve = mysqli_fetch_assoc($result)) {
            // print_r($reserve);
        ?>
            <tr>
                <td>
                    <?= $reserve['movie_name'] ?>
                </td>
                <td>
                    <?= $reserve['name'] ?>
                </td>
            </tr>

        <?php
        }
        ?>
    </table>

</body>

</html>