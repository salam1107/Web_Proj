<?php
    session_start();
    require_once('../secure/config.php');
    class Exam
    {
        public $exam_name;
        public $questions;
    }
    class Question
    {
        public $question_id;
        public $question_content;
        public $correct_answers;
        public $answers;
        public $question_type;
    }
    $exam_id = $_POST['exam_id'];
    $get_exam_name = mysqli_query($connect, "SELECT exam_name FROM exams WHERE exam_id = $exam_id");
    $exam_name = mysqli_fetch_row($get_exam_name);
    $exam_json = new Exam();
    $exam_json->exam_name = $exam_name[0];
    $get_exam_questions = mysqli_query($connect, "SELECT question_id, question_content, correct_answers, question_type FROM questions WHERE exam_id = $exam_id"); 
    $question_count = 0;
    $questions = [];
    while($question = mysqli_fetch_assoc($get_exam_questions))
    {
        $question_json = new Question();
        $question_json->question_content = $question['question_content'];
        $question_json->correct_answers = $question['correct_answers'];
        $question_json->question_type = $question['question_type'];
        $q_id = $question['question_id'];
        $question_json->question_id = $q_id;
        $get_question_answers = mysqli_query($connect, "SELECT answer_content FROM answers WHERE question_id = $q_id"); 
        $answer_count = 0;
        while($answer = mysqli_fetch_assoc($get_question_answers))
        {
            $question_json->answers[$answer_count] = ($answer['answer_content']);
            $answer_count++;
        }
        $questions[$question_count] = $question_json;
        $question_count++;
    }
    $exam_json->questions = $questions;
    echo json_encode($exam_json);
?>