<?php
    session_start();
    require_once('../secure/config.php');
    $exam_id = $_POST["exam_id"];
    $delete_answers = mysqli_query($connect, "DELETE FROM answers WHERE exam_id='$exam_id'");
    $delete_questions = mysqli_query($connect, "DELETE FROM questions WHERE exam_id='$exam_id'");
    $delete_exam = mysqli_query($connect, "DELETE FROM exams WHERE exam_id='$exam_id'");
?>