<?php
    session_start();
    require_once('../secure/config.php');
    $email = mysqli_real_escape_string($connect, $_POST['email']);
    $password = mysqli_real_escape_string($connect, $_POST['password']);
    if($email == null || $password == null)
        echo 'Please fill out all fields.';
    else
    {
        $get_info = mysqli_query($connect , "SELECT id, password FROM accounts WHERE email='$email'");
        $info = mysqli_fetch_row($get_info);
        if($password == $info[1])
        {
            $_SESSION['id'] = $info[0];
            echo'<script> window.location="pages/home.php"; </script>';
        }
        else
            echo 'Email or Password is incorrect';
    }
?>
