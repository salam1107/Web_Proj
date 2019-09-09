<div id="left_bar">
    <button id="create_new_exam_btn">Create new exam</button>
</div>
<script>
$(document).ready(function(){

    $("#create_new_exam_btn").click(function(){
        $.ajax({
            url: "/AJAX/create_exam.php",
            cache: false,
            beforeSend: function(){
                $('#loading').html('<img src="/images/loading.gif" width= 30px>');
            },
            success:function(rt){
                $('#page_content').html(rt);
                $('#loading').html('');
                history.pushState({}, null, '/pages/create_exam.php');
            }
        });
    });

});
</script>