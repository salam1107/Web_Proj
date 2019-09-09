<?php
    require_once("../secure/config.php");
    $exam_id = $_GET['exam_id'];
    $get_exam = mysqli_query($connect , "SELECT exam_name, isPublished FROM exams WHERE exam_id='$exam_id'");
    $get_questions = mysqli_query($connect , "SELECT question_id, question_content, question_type FROM questions WHERE exam_id='$exam_id'");
    $exam = mysqli_fetch_assoc($get_exam);
    if(mysqli_num_rows($get_exam) == 0)
    {
        echo '<div id="error_msg">Could not found this exam in our server</div>';
        die;
    }
    else if($exam['isPublished'] == 0)
    {
        echo '<div id="error_msg">Oops you can not see an unpublished exam</div>';
        die;
    }
    echo '<div class="exam_viewer">'.
    '<span id="ev_exam_name">'.$exam['exam_name'].'</span>'.
    '<br/></br>';
    $question_num = 1;
    while($question = mysqli_fetch_assoc($get_questions))
    {
        echo '<div class="question" id="'.$question['question_id'].'">';
        $question_id = $question['question_id'];
        if($question['question_type'] != 0)
            $get_answers = mysqli_query($connect , "SELECT answer_content FROM answers WHERE exam_id='$exam_id' AND question_id='$question_id'");
        echo '<span class="question_content">'.$question_num.') '.$question['question_content'].'</span><br/>';
        //0- textbox question
        //1- radio question
        //2- checkbox question
        echo '<div class="answers">';
        switch($question['question_type'])
        {
            case 0: //textbox question
                echo '<textarea type="textarea" id="answer_textarea" name="question'.$question_num.'" placeholder="Enter answer here"/>';
                break;
            case 1: //radio question
                while($answer = mysqli_fetch_assoc($get_answers))
                {
                    echo '<input type="radio" name="question'.$question_num.'"/>'.
                    ' <span id="radio_answer">'.$answer['answer_content'].'</span><br/>';
                }
                break;
            case 2: //checkbox question
                while($answer = mysqli_fetch_assoc($get_answers))
                {
                    echo '<input type="checkbox" name="question'.$question_num.'"/>'.
                    ' <span id="checkbox_answer">'.$answer['answer_content'].'</span><br/>';
                }
                break;
        }
        echo '</div>';
        $question_num++;
        echo '</div>';
    }
    echo '</div>';
?>
<div id="exam_viewer_bottom_bar">
    <button id="check_grade">Check my grade</button>
</div>
<script>
$(document).ready(function(){
    
    $("#check_grade").click(function(){
        var exam_id = <?php echo $exam_id; ?>;
        var answers = new Object();
        $(".question").each(function(){
            var question_id = $(this).attr("id");
            var collected_answers =  "";
            var question_answers = $(this).children(".answers").children();
            question_answers.each(function(){
                if($(this).attr("type") == "radio" || $(this).attr("type") == "checkbox")
                {
                    if($(this).prop("checked"))
                    {
                        collected_answers += $(this).next("span").text()+', ';
                    }
                }
                else if($(this).attr("type") == "textarea")
                {
                    collected_answers = $(this).val();
                }
            });
            answers[question_id] = collected_answers;
        });
        $.ajax({
            url: "/AJAX/grade_viewer.php",
            cache: false,
            type: "post",
            data: {exam_id: exam_id, answers: answers},
            beforeSend: function(){
                $('#loading').html('<img src="/images/loading.gif" width= 30px>');
            },
            success:function(rt){
                $('#page_content').html(rt);
                $('#loading').html('');
            }
        });
    });

});
</script>