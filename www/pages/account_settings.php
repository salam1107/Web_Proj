<?php
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

    $.ajax({
        url: "/AJAX/account_settings.php",
        cache: false,
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