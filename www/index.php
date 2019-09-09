<?php
    session_start();
    if(isset($_SESSION['id']))
    {
        header('Location: /pages/home.php');
        die;
    }
    require_once('./secure/config.php');
?>
<html>
    <head>
        <title><?php echo $website_name; ?></title>
        <link rel="stylesheet" type="text/css" href="/css/login_page.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    </head>
    <body>
        <div id="logo">
            <img src="./images/logo.png" />
        </div>
        <div class="images_slideshow">
            <img class="slides fade" src="images/slide1.png">
            <img class="slides fade" src="images/slide2.png">
            <img class="slides fade" src="images/slide3.png">
            <img class="slides fade" src="images/slide4.png">
        </div>
        <div id="login_div">
            <div id="left">
                <label id="login_text">Login</label>
                <br/><br/>
                <div id="login_loading"></div>
                <br/>
                <form>
                    <label class="left_login">Email</label> <input id="lemail" class="right_login" type="email" placeholder="Enter your email"><br/><br/>
                    <label class="left_login">Password</label> <input id="lpassword" class="right_login" type="password" placeholder="Enter your password"><br/><br/>
                    <button id="login_btn">Login</button>
                </form>
                <span id="forgot_password">Forgot password?</span>
            </div>
            <div id="right">
                <label id="login_text">
                    Don't have an account<br/>
                    REGISTER NOW!
                </label>
                <br/><br/>
                <div id="register_loading"></div>
                <br/>
                <form>
                    <label class="left_register">First Name</label> <input type="text" id="fname" class="right_register" autocomplete="given-name"><br/><br/>
                    <label class="left_register">Last Name</label> <input type="text" id="lname" class="right_register" autocomplete="given-name"><br/><br/>
                    <label class="left_register">Email</label> <input id="remail" class="right_register" type="email"><br/><br/>
                    <label class="left_register">Password</label> <input id="rpassword" class="right_register" type="password"><br/><br/>
                    <button id="register_btn">Register</button>
                </form>
            </div>
        </div>
    </body>
</html>
<script>
$(document).ready(function(){

    $("form").submit(function(e){
        e.preventDefault();
    });

    $('#login_btn').click(function(){
        var email = $('#lemail').val();
        var password = $('#lpassword').val();
        $.ajax({
            url: "/AJAX/check_login.php",
            cache: false,
            type: "POST",
            data: {email: email, password: password},
            beforeSend: function(){
                $('#login_btn').hide();
                $('#login_loading').html('<img src="/images/loading.gif" width= 30px>');
            },
            success:function(rt){
                $('#login_loading').html(rt);
                $('#login_btn').show();
            }
        });
    });

    
    $('#register_btn').click(function(){
        var fname = $('#fname').val();
        var lname = $('#lname').val();
        var email = $('#remail').val();
        var password = $('#rpassword').val();
        $.ajax({
            url: "/AJAX/check_register.php",
            cache: false,
            type: "POST",
            data: {fname: fname, lname: lname, email: email, password: password},
            beforeSend: function(){
                $('#register_btn').hide();
                $('#register_loading').html('<img src="/images/loading.gif" width= 30px>');
            },
            success:function(rt){
                $('#register_loading').html(rt);
                $('#register_btn').show();
            }
        });
    });

    $('#forgot_password').click(function(){
        $('#left').load("/AJAX/forgot_password.php");
    });

});

var slideIndex = 0;
carousel();

function carousel()
{
  var i;
  var x = document.getElementsByClassName("slides");
  for (i = 0; i < x.length; i++)
  {
    x[i].style.display = "none";  
  }
  slideIndex++;
  if (slideIndex > x.length)
  {
    slideIndex = 1;
  }
  x[slideIndex-1].style.display = "block";  
  setTimeout(carousel, 3000);
}
</script>