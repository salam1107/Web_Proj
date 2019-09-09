<?php
    session_start();
    require_once('../secure/config.php');
    $my_id = $_SESSION["id"];
    $exam_id = $_POST['exam_id'];
    $answers = $_POST['answers'];
    $keys = array_keys($answers);
    $exam_max_grade = 0;
    $final_grade = 0;
    $correct_answers_count = 0;
    $get_exam_data = mysqli_query($connect , "SELECT grade FROM questions WHERE exam_id='$exam_id'");
    while($grade = mysqli_fetch_assoc($get_exam_data))
    {
        $exam_max_grade += $grade['grade'];
    }
    $questions_count = mysqli_num_rows($get_exam_data);
    $question_number = 1;
    foreach($keys as $key)
    {
        $question_correct_answers_count = 0;
        $collected_answers = rtrim($answers[$key], ', ');
        $collected_answers = explode(', ', $collected_answers);
        $get_correct_answers = mysqli_query($connect , "SELECT correct_answers, grade FROM questions WHERE question_id='$key'");
        $correct_answers = mysqli_fetch_assoc($get_correct_answers);
        $question_grade = $correct_answers['grade'];
        $correct_answers = rtrim($correct_answers['correct_answers'], ', ');
        $correct_answers = explode(', ', $correct_answers);
        $question_grade /= count($correct_answers);
        $is_inc_count = 0;
        foreach($collected_answers as $answer)
        {
            if(in_array($answer, $correct_answers))
            {
                $final_grade += $question_grade;
                if($is_inc_count == 0)
                {
                    $correct_answers_count++;
                    $is_inc_count = 1;
                }
            }
        }
        if(count($correct_answers) < count($collected_answers))
        {
            $correct_answers_count--;
            $final_grade -= (count($collected_answers)-count($correct_answers))*$question_grade;
        }
        else if(count($correct_answers) > count($collected_answers))
        {
            $correct_answers_count--;
        }
        else if($is_inc_count == 1)
        {
            $insert_question_user_answer = mysqli_query($connect , "INSERT INTO question_users_answers (user_id, question_id, exam_id, isCorrect) VALUES ('$my_id', '$key', '$exam_id', 1)");
            $get_users_correct_answers_number = mysqli_query($connect , "SELECT qua_id, isCorrect FROM question_users_answers WHERE question_id='$key'");
            while($users_correct_answers_number = mysqli_fetch_assoc($get_users_correct_answers_number))
            {
                if($users_correct_answers_number['isCorrect'] == 1)
                    $question_correct_answers_count += 1;
            }
            $question_correct_answers_percent = round($question_correct_answers_count/mysqli_num_rows($get_users_correct_answers_number)*100);
            echo '<script>
            $(document).ready(function(){

                $("#statics_row").append("<td><div class='."static_height".' question_num='."$question_number".' height='."$question_correct_answers_percent".'></div></td>");
                $("#questions_num_row").append("<td>'.$question_number.'</td>");

            });
            </script>';
        }
        else
        {
            $insert_question_user_answer = mysqli_query($connect , "INSERT INTO question_users_answers (user_id, question_id, exam_id, isCorrect) VALUES ('$my_id', '$key', '$exam_id', 0)");
            $get_users_correct_answers_number = mysqli_query($connect , "SELECT qua_id, isCorrect FROM question_users_answers WHERE question_id='$key'");
            while($users_correct_answers_number = mysqli_fetch_assoc($get_users_correct_answers_number))
            {
                if($users_correct_answers_number['isCorrect'] == 1)
                    $question_correct_answers_count += 1;
            }
            $question_correct_answers_percent = round($question_correct_answers_count/mysqli_num_rows($get_users_correct_answers_number)*100);
            echo '<script>
            $(document).ready(function(){

                $("#statics_row").append("<td><div class='."static_height".' question_num='."$question_number".' height='."$question_correct_answers_percent".'></div></td>");
                $("#questions_num_row").append("<td>'.$question_number.'</td>");

            });
            </script>';
        }
        $question_number++; 
    }

    $my_grade = to_100_percent($final_grade, $exam_max_grade);
    $insert_grade = mysqli_query($connect, "INSERT INTO exam_grades (exam_id, user_id, grade) VALUES ('$exam_id', '$my_id', $my_grade)");
    $get_exam_grades_avg = mysqli_query($connect, "SELECT grade FROM exam_grades WHERE exam_id='$exam_id'");
    $all_grades_sum = 0;
    $highest_grade = 0;
    while($grades = mysqli_fetch_assoc($get_exam_grades_avg))
    {
        if($grades['grade'] > $highest_grade)
            $highest_grade = $grades['grade'];
        $all_grades_sum += $grades['grade'];
    }
    $exam_grades_avg = round($all_grades_sum/mysqli_num_rows($get_exam_grades_avg));

    function to_100_percent($grade, $max)
    {
        if($grade > $max)
            return false;
        return round(($grade/$max)*100);
    }
?>
<div class="grade_viewer">
    <div id="grade_bg_circle">
        <span id="grade_percent"><?php echo to_100_percent($final_grade, $exam_max_grade).'/100'; ?></span>
    </div>
    <div id="grade_info">
        <span id="left">Grade:</span>  <span id="right"><?php echo $final_grade.'/'.$exam_max_grade;?></span><br/>
        <span id="left">Fully correct answers:</span>  <span id="right"><?php echo $correct_answers_count.'/'.$questions_count;?></span>
    </div>
    <div id="exam_statics">
        <div id="left">
            <span>Highest grade</span>
            <span>Grades AVG.</span>
        </div>
        <div id="right">
            <div class="static" id="highest_grade"><span></span></div>
            <div class="static" id="exam_grades_avg"><span></span></div>
        </div>
    </div>
    <span id="question_static_title">Questions statics</span>
    <table id="questions_static">
        <tr id="statics_row">
        </tr>
        <tr id="questions_num_row">
        </tr>
    </table>
</div>
<script>
$(document).ready(function(){

    progressBar("#exam_grades_avg", <?php echo $exam_grades_avg; ?>);
    progressBar("#highest_grade", <?php echo $highest_grade; ?>);

    $(document).scroll(function (){
        $("#statics_row td").children(".static_height").each(function(){
            progressBarHeight($(this), $(this).attr("height"));
        });
    });

    var popup_question_hover_interval;

    $(".static_height").hover(function(){
        let text_percent = $(this)[0].style.height;
        var center = ($(this).width()/2)+5+'px';
        $(this).append('<div class="popup_question_static">'+
        '<span class="arrow-down"></span>'+
        '<span id="popup_question_text">'+
        'Question Number: '+$(this).attr("question_num")+'<br/>'+
        text_percent+" Of the people solved this correctly."+
        '</span></div>');
        $('.arrow-down').css("left", center);
    }, function(){
        clearInterval(popup_question_hover_interval);
        $(this).children(".popup_question_static").remove();
    });

    function progressBar(id, percent)
    {
        $(id).children("span").text("1%");
        var i = 0;
        var interval = setInterval(function(){
            if(i < percent)
            {
                i++;
            }
            else
            {
                clearInterval(interval);
            }
            $(id).width(percent+"%");
            $(id).children("span").text(i+"/100");
        }, 2000/percent);
    }

    function progressBarHeight(id, percent)
    {
        $(id).height(percent+"%");
    }

});
</script>