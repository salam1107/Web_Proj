<?php
    session_start();
    require_once('../secure/config.php');
    $id = $_SESSION["id"];
    $firstname = mysqli_real_escape_string($connect, $_POST['firstname']);
    $update_firstname = mysqli_query($connect, "UPDATE accounts SET Firstname='$firstname' WHERE id='$id'");
?>