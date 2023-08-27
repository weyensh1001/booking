<html lang="zh-Hant">

<head>
    <title>Seat Reservation</title>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="css/w3.css">
    <style>
        .seatbox {
            width: 60px;
            height: 60px;
            margin: 8px;
            border: 2px solid black;
            background-color: green;
            text-align: center;
            line-height: 80px;
            display: inline-block;
            color: white;
        }

        .reserved {
            background-color: red;
        }

        .inputseat {
            margin: 0 0 15px 15px;
            border: 1px solid black;
            padding: 8px;
            border-radius: 6px;
        }

        .content {
            height: 550px;
            width: 65%;
            margin: 0 0 0 270px;
        }

        .screen {
            height: 30px;
            width: 60%;
            background-color: black;
            margin: 20 0 0 310px;
            border-radius: 5px;
        }

        .screen-name {
            color: white;
            font-size: 25px;
            margin: 0 0 0 400px;
        }
    </style>

</head>

<body class="w3-white ">
    <?php
    include('header.php');

    $m_id = $_GET['movie_id'];
    echo $m_id;
    echo $_SESSION['user'];
    ?>
    <div class="w3-white w3-padding-16">
        <div class="w3-center">
            <div class="content">
                <?php
                // echo $_GET['movie_id'];
                $q_seat = "select * from seat";
                $result = mysqli_query($con, $q_seat);
                while ($seat = mysqli_fetch_assoc($result)) {
                    // print_r($seat)
                    $q_reserever = "SELECT * from reserved_seat where movie_id = " . $m_id . " and seat_id = " . $seat['id'];
                    $r_result = mysqli_query($con, $q_reserever);
                    // echo $q_reserever;
                    // echo $con->error;
                    if (mysqli_num_rows($r_result) == 0) {
                ?>

                        <div class="seatbox" id="">
                            <?= $seat['name'] ?>
                        </div>
                    <?php
                    } else { ?>
                        <div class="seatbox reserved" id="">
                            <?= $seat['name'] ?>
                        </div>
                    <?php
                    }
                    ?>
                <?php
                }
                ?>
            </div>
        </div>
        <div class="screen">
            <p class="screen-name">screen</p>
        </div>
    </div>
    <form class="w3-white" action="confirmseat.php" method="POST">
        <p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Green=available&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Red=reserved</p>
        <input type="hidden" name="charge" value="<?= $_GET['charge'] ?>" >
        <input type="text" class="inputseat" name="seat_name" placeholder="input seat number">
        <input type="hidden" name="movie_id" value="<?= $m_id ?>">
        <input type="hidden" name="user_id" value="<?= $_SESSION['user'] ?>">
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<button class="w3-button w3-white w3-border w3-border-red w3-round-large" type="submit" name="submitbutton" id="submitbutton">Confirm</button>
    </form>
    <script>
        // const reserve = document.getElementsByClassName('reserved');
        // if (reserve.innerHTML = "") {
        //     alert("already booked");
        // }
    </script>
</body>

</html>