<?php
    require_once('../secure/config.php');
    $answer_content = mysqli_real_escape_string($connect, $_POST['content']);
    $question_id = $_POST['question_id'];
    $exam_id = $_POST['exam_id'];
    $insert_answer = mysqli_query($connect, "INSERT INTO answers(answer_content, question_id, exam_id) VALUES ('$answer_content', '$question_id', '$exam_id')");
?>