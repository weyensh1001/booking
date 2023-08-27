<?php

    require("config.php");
    $res_id = $_POST['res_id'];
    $sql = "UPDATE `reserved_seat` SET `status`='1' WHERE id = $res_id";

    $con -> query($sql);

?>