<?php
    session_start();
    require_once("../secure/config.php");
    $pid = $_POST['pid'];
    if($pid == $_SESSION["id"]) //owner
    {
        $get_exams = mysqli_query($connect , "SELECT exam_id, exam_name, isPublished FROM exams WHERE createdById='$pid' ORDER BY exam_id DESC");
    }
    else //visitor can see only published exams
    {
        $get_exams = mysqli_query($connect , "SELECT exam_id, exam_name FROM exams WHERE createdById='$pid' AND isPublished = 1 ORDER BY exam_id DESC");
    }
    echo '<div id="profile_exams_section">';
    while($exam = mysqli_fetch_assoc($get_exams))
    {
        echo '<div class="profile_exams_exam" id="'.$exam["exam_id"].'">'.
        $exam['exam_name'].'<br/>';
        $exam_id = $exam['exam_id'];
        $get_questions_num = mysqli_query($connect , "SELECT question_id FROM questions WHERE exam_id='$exam_id'");
        $questions_num = mysqli_num_rows($get_questions_num);
        echo $questions_num.'<br/>'.
        'Questions'.'<br/>'.
        '</div>';
    }
    echo '</div>';
?>
<script>
$(document).ready(function(){

    $(".profile_exams_exam").click(function(){
        var exam_id = $(this).attr("id");
        $.ajax({
            url: "/AJAX/exam_details.php",
            cache: false,
            type: "GET",
            data: {exam_id: exam_id},
            beforeSend: function(){
            $('#loading').html('<img src="/images/loading.gif" width= 30px>');
            },
            success:function(rt){
                $('#page_content').html(rt);
                $('#loading').html('');
                history.pushState({}, null, '/pages/exam_details.php?exam_id='+exam_id);
            }
        });
    });

});
</script>