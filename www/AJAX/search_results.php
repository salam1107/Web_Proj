<?php
    session_start();
    require_once('../secure/config.php');
    $value = $_POST['val'];
    $vals = explode(' ', $value);
    $get_results = mysqli_query($connect, "SELECT id, Firstname, Lastname FROM accounts WHERE (Firstname LIKE '$vals[0]%' AND Lastname LIKE '$vals[1]%') OR (Firstname LIKE '$vals[1]%' AND Lastname LIKE '$vals[0]%') OR (Firstname LIKE '$vals[0]%') OR (Lastname LIKE '$vals[0]%')");
    while( $result = mysqli_fetch_assoc($get_results) )
    {
      echo '<div class="search_result" id="'.$result['id'].'">'.$result['Firstname'].' '.$result['Lastname'].'</div>';
    }
?>
<script>
$(document).ready(function(){
   
   $('.search_result').click(function(){
      var pid = $(this).attr("id");
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
              $('#search_bar').val('');
              history.pushState({}, null, '/pages/profile.php?pid='+pid);
          }
      });
   });
   
});
</script>
