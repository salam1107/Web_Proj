<img id="go_back" src="../images/back.png"/>
<label id="forgotpass_text">Forgot password?</label>
<br/><br/>
<div id="login_password_forgot"></div>
<br/>
<label class="left_login">Email</label> <input id="lemail" class="right_login" type="email" placeholder="Enter your email"><br/><br/>
<button id="login_btn">Submit</button>
<script>
$(document).ready(function(){
    
    $('#go_back').click(function(){
      $('#left').load("/AJAX/login.php");
    });
    
}); 
</script>