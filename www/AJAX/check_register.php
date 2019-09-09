<?php
    session_start();
    require_once('../secure/config.php');
    $fname = mysqli_real_escape_string($connect, $_POST['fname']);
    $lname = mysqli_real_escape_string($connect, $_POST['lname']);
    $email = mysqli_real_escape_string($connect, $_POST['email']);
    $password = mysqli_real_escape_string($connect, $_POST['password']);
    if($fname == null || $lname == null || $email == null || $password == null)
    {
        echo 'Please fill out all fields.';
    }
    else
    {
        $check_email = mysqli_query($connect, "SELECT id FROM accounts WHERE email='$email'");
        $isAlready = mysqli_fetch_row($check_email);
        if($isAlready[0])
        {
            echo 'This email is already exists.';
        }
        else
        {
            $insert_info = mysqli_query($connect, "INSERT INTO accounts(Firstname, Lastname, email, password) VALUES ('$fname', '$lname', '$email', '$password')");
            $get_id = mysqli_query($connect, "SELECT id FROM accounts WHERE email='$email'");
            $id = mysqli_fetch_row($get_id);
            $_SESSION['id'] = $id[0];
            echo'<script> window.location="pages/home.php"; </script>';
        }
    }
?>