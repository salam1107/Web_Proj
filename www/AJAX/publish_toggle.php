<?php
    session_start();
    require_once('../secure/config.php');
    $exam_id = $_POST['exam_id'];
    $update_firstname = mysqli_query($connect, "UPDATE exams SET date_published = IF(isPublished = 0, CURTIME(), date_published), isPublished=1-isPublished WHERE exam_id='$exam_id'");
?>