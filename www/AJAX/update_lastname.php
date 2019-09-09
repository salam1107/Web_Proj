<?php
    session_start();
    require_once('../secure/config.php');
    $id = $_SESSION["id"];
    $lastname = mysqli_real_escape_string($connect, $_POST['lastname']);
    $update_lastname = mysqli_query($connect, "UPDATE accounts SET Lastname='$lastname' WHERE id='$id'");
?>