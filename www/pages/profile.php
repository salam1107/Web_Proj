<?php
    if($_GET['pid'] == null)
    {
        header('Location: /pages/home.php');
        die;
    }
    if($_GET['page'] == null)
    {
        $page = "exams";
    }
    else
    {
        $page = $_GET['page'];
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

    var pid = <?php echo $_GET['pid']; ?>; 
    var page = "<?php echo $page; ?>"; 

    $.ajax({
        url: "/AJAX/profile.php",
        cache: false,
        type: "GET",
        data: {pid: pid, page: page},
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