<?php
    session_start();
    require_once("../secure/config.php");
    $pid = $_GET['pid'];
    if($_GET['page'] == null)
    {
        $page = "exams";
    }
    else
    {
        $page = $_GET['page'];
    }
    $get_user = mysqli_query($connect, "SELECT Firstname, Lastname FROM accounts where id = '$pid'");
    if(mysqli_num_rows($get_user) == 0)
    {
        echo '<div id="error_msg">Could not found this profile in our server</div>';
        die;
    }
    $user = mysqli_fetch_assoc($get_user);
    $get_exams = mysqli_query($connect, "SELECT exam_id FROM exams where createdById = '$pid'");
    $get_published_exams = mysqli_query($connect, "SELECT exam_id FROM exams where createdById = '$pid' AND isPublished = 1");
    $exams_number = mysqli_num_rows($get_exams);
    $published_exams_number = mysqli_num_rows($get_published_exams);
?>
<div class="profile">
    <div class="left">
        <span id="user_full_name"><?php echo $user['Firstname'].' '.$user['Lastname']; ?></span>
        <div class="left_user_data">
            <span id="number"><?php echo $exams_number; ?></span>
            <span id="text">Exams created</span>
        </div>
        <div class="left_user_data">
            <span id="number"><?php echo $published_exams_number; ?></span>
            <span id="text">Exams Published</span>
        </div>
    </div>
    <div class="right">
        <div id="profile_top_bar">
            <?php
                if($pid == $_SESSION['id'])
                {
                    echo "<button id='my_exams_btn'>My Exams</button>";
                }
                else
                {
                    echo "<button>".$user['Firstname']."'s Exams</button>";
                }
            ?>
        </div>
        <div id="profile_page_content">

        </div>
    </div>
</div>
<script>
$(document).ready(function(){
    
    var pid = <?php echo $pid; ?>;
    var page = "<?php echo $page; ?>";
    
    switch(page)
    {
        case "exams":
            myExamsPage();
            break;
        default:
            myExamsPage();
            break;
    }

    $("#my_exams_btn").click(function(){
        myExamsPage();
    });

    function myExamsPage()
    {
        $.ajax({
            url: "/AJAX/profile_exams.php",
            cache: false,
            type: "POST",
            data: {pid: pid},
            beforeSend: function(){
                //something to do
            },
            success:function(rt){
                $('#profile_page_content').html(rt);
            }
        });
    }

});
</script>