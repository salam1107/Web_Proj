<?php
    session_start();
    require_once('../secure/config.php');
    $id = $_SESSION["id"];
    $get_info = mysqli_query($connect , "SELECT Firstname, Lastname, Email FROM accounts WHERE id='$id'");
    $info = mysqli_fetch_assoc($get_info);
?>
<div class="account_settings">
    <span id="settings_title">Account Settings</span>
    <div class="settings_section"> <!-- profile -->
        <div class="left">
            <span id="settings_subtitle">Profile<span>
        </div>
        <div class="right">
            <span>First Name</span>
            <input id="fname_input" type="text" placeholder="First Name" value="<?php echo $info['Firstname']; ?>"/>
            <span>Last Name</span>
            <input id="lname_input" type="text" placeholder="Last Name" value="<?php echo $info['Lastname']; ?>"/>
            <span class="warning_msg" id="profile_warning_msg"></span>
        </div>
    </div>

    <div class="settings_section"> <!-- Email -->
        <div class="left">
            <span id="settings_subtitle">Email<span>
        </div>
        <div class="right">
            <span>Email</span>
            <input id="email_input" type="email" placeholder="Email" value="<?php echo $info['Email']; ?>"/>
            <span class="warning_msg" id="email_warning_msg"></span>
        </div>
    </div>

    <div class="settings_section"> <!-- Password -->
        <div class="left">
            <span id="settings_subtitle">Password<span>
        </div>
        <div class="right">
            <form>
                <span>Old Password</span>
                <input id="old_pass_input" type="password" placeholder="Enter Old Password"/>
                <span>New Password</span>
                <input id="new_pass_input" type="password" placeholder="Enter New Password"/>
                <span>Confirm New Password</span>
                <input id="confirm_new_pass_input" type="password" placeholder="Confirm Your New Password"/>
                <span class="warning_msg" id="password_warning_msg"></span><br/>
                <button id="password_change_btn">Change Password</button>
            </form>
        </div>
    </div>

</div>
<script>
$(document).ready(function(){

    $("form").submit(function(e){
        e.preventDefault();
    });

    var last_firstname = $("#fname_input").val();
    var last_lastname= $("#lname_input").val();
    
    $("#fname_input").change(function(){
        if($(this).val() == "")
        {
            $("#profile_warning_msg").text("Please enter your first name");
            $(this).css("border-color","red");
        }
        else
        {
            var firstname = $(this).val();
            var lastname = $("#lname_input").val();

            last_firstname = firstname;
            $.ajax({
                url: "/AJAX/update_firstname.php",
                cache: false,
                type: "POST",
                data: {firstname: firstname},
                beforeSend: function(){
                    $("#fname_input").css("border-color","rgb(212, 227, 235)");
                    $('#profile_warning_msg').html('<label id="save">Saving  </label><img src="/images/loading_2.gif">');
                },
                success:function(rt){
                    $('#profile_warning_msg').html('<label id="save">Saved  </label><img src="/images/correct.png">');
                    $("#user_name_span").text(last_firstname + " " +last_lastname);
                }
            });
        }
    });

    $("#lname_input").change(function(){
        if($(this).val() == "")
        {
            $("#profile_warning_msg").text("Please enter your last name");
            $(this).css("border-color","red");
        }
        else
        {
            var lastname = $(this).val();
            var firstname = $("#fname_input").val();

            last_lastname = lastname;
            $.ajax({
                url: "/AJAX/update_lastname.php",
                cache: false,
                type: "POST",
                data: {lastname: lastname},
                beforeSend: function(){
                    $("#lname_input").css("border-color","rgb(212, 227, 235)");
                    $('#profile_warning_msg').html('<label id="save">Saving  </label><img src="/images/loading_2.gif">');
                },
                success:function(rt){
                    $('#profile_warning_msg').html('<label id="save">Saved  </label><img src="/images/correct.png">');
                    $("#user_name_span").text(last_firstname + " " +last_lastname);
                }
            });
        }
    });

    $("#email_input").change(function(){
        if($(this).val() == "")
        {
            $("#email_warning_msg").text("Please enter your e-mail");
            $(this).css("border-color","red");
        }
        else
        {
            var email = $(this).val();
            $.ajax({
                url: "/AJAX/update_email.php",
                cache: false,
                type: "POST",
                data: {email: email},
                beforeSend: function(){
                    $('#email_warning_msg').html('<label id="save">Saving  </label><img src="/images/loading_2.gif">');
                    $("#email_input").css("border-color","rgb(212, 227, 235)");
                },
                success:function(rt){
                    $('#email_warning_msg').html('<label id="save">Saved  </label><img src="/images/correct.png">');
                }
            });
        }
    });

    $("#password_change_btn").click(function(){
        if($("#old_pass_input").val() == "" || $("#new_pass_input").val() == "" || $("#confirm_new_pass_input").val() == "")
        {
            $("#password_warning_msg").text("Please enter Old password, New password and Confirm password");
            $("#old_pass_input").css("border-color","red");
            $("#new_pass_input").css("border-color","red");
            $("#confirm_new_pass_input").css("border-color","red");
        }
        else
        {
            var old_password = $("#old_pass_input").val();
            var new_password = $("#new_pass_input").val();
            var confirm_new_password = $("#confirm_new_pass_input").val();
            if(old_password == new_password && new_password == confirm_new_password)
            {
                $("#password_warning_msg").text("Your New password is the same as your Old password");
                $("#old_pass_input").css("border-color","red");
                $("#new_pass_input").css("border-color","red");
                $("#confirm_new_pass_input").css("border-color","red");
            }
            else if(new_password != confirm_new_password)
            {
                $("#password_warning_msg").text("New password and Confirm password do not match!");
                $("#old_pass_input").css("border-color","red");
                $("#new_pass_input").css("border-color","red");
                $("#confirm_new_pass_input").css("border-color","red");
            }
            else
            {
                $.ajax({
                    url: "/AJAX/update_password.php",
                    cache: false,
                    type: "post",
                    data: {old_password: old_password, new_password: new_password},
                    beforeSend: function(){
                        $('#password_warning_msg').html('<label id="save">Saving  </label><img src="/images/loading_2.gif">');
                    },
                    success:function(rt){
                        if(rt != 0)
                        {
                            $('#password_warning_msg').html("Your old password is incorrect");
                            $("#old_pass_input").css("border-color","red");
                            $("#new_pass_input").css("border-color","red");
                            $("#confirm_new_pass_input").css("border-color","red");
                        }
                        else
                        {
                            $('#password_warning_msg').html('<label id="save">Your password has been changed successfully  </label><img src="/images/correct.png">');
                            $("#old_pass_input").css("border-color","rgb(212, 227, 235)");
                            $("#new_pass_input").css("border-color","rgb(212, 227, 235)");
                            $("#confirm_new_pass_input").css("border-color","rgb(212, 227, 235)");
                        }
                    }
                });
            }
        }
    });

});
</script>