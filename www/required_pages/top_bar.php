<?php
    session_start();
    if(!isset($_SESSION['id']))
    {
        header('Location: /');
        die;
    }
    require_once('../secure/config.php');
    $logged_id = $_SESSION['id'];
    $query = mysqli_query($connect, "SELECT Firstname, Lastname FROM accounts where id = '$logged_id'");
    $info = mysqli_fetch_row($query);
?>
<head>
    <link rel="stylesheet" type="text/css" href="/css/top_bar.css">
    <link rel="stylesheet" type="text/css" href="/css/left_bar.css">
    <link rel="stylesheet" type="text/css" href="/css/exam_viewer.css">
    <link rel="stylesheet" type="text/css" href="/css/grade_viewer.css">
    <link rel="stylesheet" type="text/css" href="/css/exam_creator.css">
    <link rel="stylesheet" type="text/css" href="/css/edit_exam.css">
    <link rel="stylesheet" type="text/css" href="/css/exam_details.css">
    <link rel="stylesheet" type="text/css" href="/css/profile.css">
    <link rel="stylesheet" type="text/css" href="/css/account_settings.css">
    <link rel="stylesheet" type="text/css" href="/css/home.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
</head>
<div id="top_bar">
    <a href="/pages/home.php"><img id="top_bar_logo" src="../images/logo2.png"/></a>
    <input id="search_bar" type="search" placeholder="Search something here..." autocomplete="off"/>
    <div id="search_slide_down"></div>
    <button class="user_dropdown_mouseover" id="user_dropdown_btn">
        <span id="user_dropdown_btn_name">
            <span id="user_name_span"><?php echo $info[0].' '.$info[1]; ?></span>
            <img src="/images/arrow_down_icon.png"/>
        </span>
    </button>
    <div class="user_dropdown_mouseover" id="user_dropdown">
        <span class="arrow-up"></span>
        <button id="goto_profile_btn"><img src="/images/profile.png"/>My profile</button>
        <button id="settings_btn"><img src="/images/settings.png"/>Settings</button>
        <a href="/logout.php"><button><img src="/images/logout.png"/>Logout</button></a>
    </div>
</div>
<div id="top_bar_2">
    <div id="btns_bar">
        <button id="home_btn" class="top_bar2_btns"><img class="btns_icons" src="/images/home_icon.png">Home</button>
    </div>  
</div>

<script>
$(document).ready(function(){
    var position = $(window).scrollTop(); 
    $(window).scroll(function() {
        var scroll = $(window).scrollTop();
        if(scroll > position) {
            $('#top_bar_2').slideUp(100);
        } else {
            $('#top_bar_2').slideDown(100);
        }
        position = scroll;
    });

    $("#home_btn").click(function(){
        $.ajax({
            url: "/AJAX/home.php",
            cache: false,
            beforeSend: function(){
                $('#loading_exams').html('<img src="/images/loading.gif" width= 30px>');
            },
            success:function(rt){
                $('#page_content').html(rt);
                $('#loading_exams').html('');
                history.pushState({}, null, '/pages/home.php');
            }
        });
    });

    $('.user_dropdown_mouseover').mouseover(function(){
        $('#user_dropdown').show();
    });
    
    $('.user_dropdown_mouseover').mouseout(function(){
        $('#user_dropdown').hide();
    });

    $("#settings_btn").click(function(){
        $.ajax({
            url: "/AJAX/account_settings.php",
            cache: false,
            beforeSend: function(){
                $('#loading').html('<img src="/images/loading.gif" width= 30px>');
            },
            success:function(rt){
                $('#page_content').html(rt);
                $('#loading').html('');
                history.pushState({}, null, '/pages/account_settings.php');
            }
        });
    });

    $("#goto_profile_btn").click(function(){
        var pid = <?php echo $_SESSION['id']; ?>; 
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

    $("#user_dropdown").children("button").click(function(){
        $("#user_dropdown").hide();
    });

    $('#search_bar').keyup(function(){
        if($(this).val() != '')
        {
            $('#search_slide_down').slideDown(100);
            search_val = $('#search_bar').val();
            $.ajax({
                url: "/AJAX/search_results.php",
                cache: false,
                type: "POST",
                data: {val: search_val},
                success: function(rt){
                  $('#search_slide_down').html(rt);
                }
            });
        }
        if($('#search_bar').val() == '')
            $('#search_slide_down').slideUp(100);
    });

    $(window).click(function(){
        $('#search_slide_down').slideUp(100);
    });


    $('#search_bar').click(function(e){
        e.stopPropagation();
        if($('#search_bar').val() != '')
            $('#search_slide_down').slideDown(100);
    });


});
</script>