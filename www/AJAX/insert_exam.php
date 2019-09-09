<?php
    session_start();
    require_once('../secure/config.php');
    $id = $_SESSION["id"];
    $exam_name = mysqli_real_escape_string($connect, $_POST['name']);
    $insert_exam = mysqli_query($connect, "INSERT INTO exams(exam_name, date_created, createdById) VALUES ('$exam_name', CURTIME(), '$id')");
    echo mysqli_insert_id($connect);
?>