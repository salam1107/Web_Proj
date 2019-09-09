<?php
    if($_GET['exam_id'] == null)
    {
        header('Location: /pages/home.php');
        die;
    }
    require_once('../secure/config.php');
    require_once('../required_pages/top_bar.php');
    require_once('../required_pages/left_bar.php');
?>
<html>
    <head>
        
    </head>
    <body>
        <div id="page_content">
        </div>
        <div id="loading"></div>
    </body>
</html>
<script>
$(document).ready(function(){

    var exam_id = <?php echo $_GET['exam_id']; ?>; 
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
        }
    });

});
</script>