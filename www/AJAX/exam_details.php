<?php
    session_start();
    require_once("../secure/config.php");
    $exam_id = $_GET['exam_id'];
    $get_exam = mysqli_query($connect , "SELECT exam_name, date_created, date_published, isPublished, createdById FROM exams WHERE exam_id='$exam_id'");
    $exam = mysqli_fetch_assoc($get_exam);
    $get_questions_num = mysqli_query($connect , "SELECT question_id FROM questions WHERE exam_id='$exam_id'");
    $questions_num = mysqli_num_rows($get_questions_num);
    $created_date = date_create($exam['date_created']);
    $published_date = date_create($exam['date_published']);
?>
<div class="exam_settings">
    <span id="exam_settings_title">Exam Details</span>
    <div id="exam_info">
        <div id="left">
            <span>Exam name:<span>
            <span>contains:<span>
            <span>Created on date:<span>
            <span>At:<span>
            <span>last publish date:<span> 
            <span>At:<span> 
            <span>Status<span>
        </div>
        <div id="right">
            <span><?php echo $exam['exam_name']; ?></span>
            <span><?php echo $questions_num; ?> questions</span>
            <span><?php echo date_format($created_date, "d M Y"); ?></span>
            <span><?php echo date_format($created_date, "H:i"); ?></span>
            <span id="published_date"><?php echo $exam['date_published'] == null ? "Never" : date_format($published_date, "d M Y"); ?></span>
            <span id="published_time"><?php echo $exam['date_published'] == null ? "Never" : date_format($published_date, "H:i"); ?></span>
            <span id="status"><div id="isPublish_dot" style="background-color: green;"></div><span id="status_text"></span></span>
        </div>
    </div>
</div>
<?php 
    if($exam['createdById'] == $_SESSION['id'])
    {
        echo '<div id="exam_settings_bottom_bar">'.
            '<button id="edit_exam_btn">Edit exam</button>'.
            '<button id="publish_btn">publish</button>'.
            '<button id="delete_exam_btn">Delete exam</button>'.
            '</div>';
    }
?>
<script>
$(document).ready(function(){

    var exam_id = <?php echo $exam_id; ?>;
    var status = <?php echo $exam['isPublished']; ?>;

    if(status == 0)
    {
        $("#isPublish_dot").css("background-color", "red");
        $("#status_text").text("Unpublished");
        $("#publish_btn").text("Publish");
    }
    else
    {
        $("#isPublish_dot").css("background-color", "green");
        $("#status_text").text("Published");
        $("#publish_btn").text("Unpublish");
    }

    $("#edit_exam_btn").click(function(){
        $.ajax({
            url: "/AJAX/edit_exam.php",
            cache: false,
            type: "GET",
            data: {exam_id: exam_id},
            beforeSend: function(){
                $('#loading').html('<img src="/images/loading.gif" width= 30px>');
            },
            success: function(rt){
                $('#loading').html('');
                $("#page_content").html(rt);
                history.pushState({}, null, '/pages/edit_exam.php?exam_id='+exam_id);
            }
        });
    });

    $("#publish_btn").click(function(){
        $.ajax({
            url: "/AJAX/publish_toggle.php",
            cache: false,
            type: "POST",
            data: {exam_id: exam_id},
            beforeSend: function(){
                //do something
            },
            success:function(rt){
               if(status == 0)
               {
                    var curr_time = new Date();
                    var monthShortNames = ["Jan", "Feb", "Mar", "Apr", "May", "Jun",
                    "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];
                    status = 1 - status;
                    $("#isPublish_dot").css("background-color", "green");
                    $("#status_text").text("Published");
                    $("#publish_btn").text("Unpublish");
                    $("#published_date").text(curr_time.getDate()+" "+monthShortNames[curr_time.getMonth()]+" "+curr_time.getFullYear());
                    $("#published_time").text(curr_time.getHours()+":"+curr_time.getMinutes());
               }
               else
               {
                    status = 1 - status;
                    $("#isPublish_dot").css("background-color", "red");
                    $("#status_text").text("Unpublished");
                    $("#publish_btn").text("Publish");
               }
            }
        });
    });

    $("#delete_exam_btn").click(function(){
        $.ajax({
            url: "/AJAX/delete_exam.php",
            cache: false,
            type: "POST",
            data: {exam_id: exam_id},
            beforeSend: function(){
                //something to do
            },
            success:function(rt){
                $("#page_content").load("/AJAX/profile.php?pid="+<?php echo $_SESSION['id']; ?>);
                history.pushState({}, null, '/pages/profile.php?pid='+<?php echo $_SESSION['id']; ?>);
            }
        });
    });

});
</script>