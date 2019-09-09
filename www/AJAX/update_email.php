<?php
    session_start();
    require_once('../secure/config.php');
    $id = $_SESSION["id"];
    $email = mysqli_real_escape_string($connect, $_POST['email']);
    $update_email = mysqli_query($connect, "UPDATE accounts SET Email='$email' WHERE id='$id'");
?>