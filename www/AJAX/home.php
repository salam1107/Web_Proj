<?php
    session_start();
    require_once("../secure/config.php");
    $get_published_exams = mysqli_query($connect, "SELECT exam_id, exam_name, createdById FROM exams WHERE isPublished=1 ORDER BY exam_id DESC");
    while($published_exams = mysqli_fetch_assoc($get_published_exams))
    {
        echo '<div class="exam" id="'.$published_exams['exam_id'].'">'.
        '<span id="publish_title">Published by <span class="publisher_name" id="'.$published_exams['createdById'].'">';
        if($published_exams['createdById'] == $_SESSION['id'])
        {
            echo 'You';
        }
        else
        {
            $publisherId = $published_exams['createdById'];
            $get_publisher_data = mysqli_query($connect, "SELECT Firstname, Lastname FROM accounts WHERE id='$publisherId'");
            $publisher = mysqli_fetch_assoc($get_publisher_data);
            echo $publisher['Firstname'].' '.$publisher['Lastname'];
        }
        echo '</span></span>'.
        '<br/><br/>'.
        '<span id="exam_name">'.$published_exams['exam_name'].'</span>'.
        '<br/><br/>'.
        '<div id="exam_bottom_bar">
            <button class="preview_exam_btn">Start exam</button>
        </div>'.
        '</div>';
    }
?>
<script>
$(document).ready(function(){
    
    $(".publisher_name").click(function(){
        var pid = $(this).attr('id');
        $.ajax({
            url: "/AJAX/profile.php",
            cache: false,
            type: "GET",
            data: {pid: pid},
            beforeSend: function(){
                $('#loading').html('<img src="/images/loading.gif" width= 30px>');
            },
            success:function(rt){
                $('#page_content').html(rt);
                $('#loading').html('');
                history.pushState({}, null, '/pages/profile.php?pid='+pid);
            }
        });
    });

    $(".preview_exam_btn").click(function(){
        var exam_id = $(this).closest('.exam').attr('id');
        $.ajax({
            url: "/AJAX/exam_viewer.php",
            cache: false,
            type: "GET",
            data: {exam_id: exam_id},
            beforeSend: function(){
                $('#loading').html('<img src="/images/loading.gif" width= 30px>');
            },
            success:function(rt){
                $('#page_content').html(rt);
                $('#loading').html('');
                history.pushState({}, null, '/pages/exam_viewer.php?exam_id='+exam_id);
            }
        });
    });

});
</script>