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
    
    $('#forgot_password').click(function(){
        $('#left').load("/AJAX/forgot_password.php");
    });

});
</script>