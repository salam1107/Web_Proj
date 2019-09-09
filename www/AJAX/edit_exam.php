<?php
    session_start();
?>
<div class="edit_exam">
    <span class="ee_exam_name"></span>
    <br/><br/>
</div>
<div id="exam_editor_bottom_bar">
    <button id="add_new_question_btn">Add new question</button>
    <button id="update_exam_btn">Update</button>
</div>
<script>
$(document).ready(function(){

    var exam_id = <?php echo $_GET['exam_id']; ?>;

    $.ajax({
        url: "/AJAX/get_exam.php",
        cache: false,
        type: "POST",
        data: {exam_id: exam_id},
        dataType: 'json',
        success: function(data){
            $(".ee_exam_name").text(data.exam_name);
            for(let i=0; i<data.questions.length; i++)
            {
                let question_div = 
                '<div class="question" id="'+data.questions[i].question_id+'">'+
                '<span class="question_content">'+data.questions[i].question_content+'</span>'+
                '<br/>'+
                '<div class="answers">';
                switch(data.questions[i].question_type)
                {
                    case 0: //textarea
                        break;
                    case 1: //radio
                        question_div += '<input type="radio" name="question'+i+'">';
                        for(let j=0; j<data.questions[i].answers.length; j++)
                        {
                            question_div += '<span id="radio_answer">'+data.questions[i].answers[j]+'</span><br/>';
                        }
                        break;
                    case 2: //checkbox
                        break;
                }
                question_div += '</div>'+
                '</div>';
                $(".edit_exam").append(question_div);
            }
        }
    })


});
</script>