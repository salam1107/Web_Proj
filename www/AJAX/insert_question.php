<?php
    require_once('../secure/config.php');
    $question_content = mysqli_real_escape_string($connect, $_POST['content']);
    $correct_answers = mysqli_real_escape_string($connect, $_POST['correct']);
    $question_type = $_POST['type'];
    $exam_id = $_POST['exam_id'];
    $insert_quesion = mysqli_query($connect, "INSERT INTO questions(question_content, correct_answers, question_type, exam_id) VALUES ('$question_content', '$correct_answers', '$question_type', '$exam_id')");
    echo mysqli_insert_id($connect);
?>