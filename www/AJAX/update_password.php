<?php
    session_start();
    require_once('../secure/config.php');
    $id = $_SESSION["id"];
    $old_password_entered = mysqli_real_escape_string($connect, $_POST['old_password']);
    $new_password = mysqli_real_escape_string($connect, $_POST['new_password']);
    $get_old_password = mysqli_query($connect, "SELECT Password FROM accounts WHERE id='$id'");
    $old_password = mysqli_fetch_assoc($get_old_password);
    if($old_password['Password'] != $old_password_entered)
    {
        echo -1;
        return;
    }
    $update_password = mysqli_query($connect, "UPDATE accounts SET Password='$new_password' WHERE id='$id'");
    echo 0;
?>