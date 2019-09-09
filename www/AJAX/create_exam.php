<?php
    session_start();
?>
<div class="exam_creator">
    <span class="ce_exam_name">Exam name</span>
    <br/><br/>
</div>
<div id="exam_creator_bottom_bar">
    <button id="add_new_question_btn">Add new question</button>
    <button id="save_exam_btn">Save</button>
</div>
<script>
$(document).ready(function(){

    var question_num = 0;

    var switchToInput = function ()
    {
        var $input = $("<textarea>", {
            val: $(this).text(),
            type: "text"
        });
        $input.addClass($(this).attr("class"));
        $(this).replaceWith($input);
        $input.on("blur", switchToSpan);
        $input.select();
    };
    var switchToSpan = function () 
    {
        var $span = $("<span>", {
            text: $(this).val()
        });
        $span.addClass($(this).attr("class"));
        $(this).replaceWith($span);
    };

    var openQuestionMenu = function ()
    {
        $(this).next(".question_dropdown_menu").show();
        $(this).on("blur", closeQuestionMenu);
    };
    
    var closeQuestionMenu = function () 
    {
        $(this).next(".question_dropdown_menu").hide();
    };

    var changeToTextarea_btn_clicked = function()
    {
        var answers = $(this).closest(".new_question").children(".new_question_answers");
        answers.empty();
        answers.append('<textarea type="textarea" id="answer_textarea" placeholder="Enter answer here"/>');
    };

    var changeToRadio_btn_clicked = function()
    {
        var question_n = $(this).closest(".new_question").attr("id");
        var answers = $(this).closest(".new_question").children(".new_question_answers");
        answers.empty();
        answers.append('<input type="radio" name="question'+question_n+'" checked/> <span class="new_radio_answer">answer 1</span>'+'<br/>'+
                       '<input type="radio" name="question'+question_n+'"/> <span class="new_radio_answer">answer 2</span>'+'<br/>'+
                       '<button class="add_new_radio_answer_btn">Add new answer</button>');
    };

    var add_new_radio_answer = function()
    {
        var question_n = $(this).closest(".new_question").attr("id");
        var answers = $(this).closest(".new_question").children(".new_question_answers");
        answers.children(".add_new_radio_answer_btn").remove();
        answers.append('<input type="radio" name="question'+question_n+'"/> <span class="new_radio_answer">New answer</span>'+'<br/>'+
                       '<button class="add_new_radio_answer_btn">Add new answer</button>');
    }

    var changeToCheckbox_btn_clicked = function()
    {
        var question_n = $(this).closest(".new_question").attr("id");
        var answers = $(this).closest(".new_question").children(".new_question_answers");
        answers.empty();
        answers.append('<input type="checkbox" name="question'+question_n+'" checked/> <span class="new_checkbox_answer">answer 1</span>'+'<br/>'+
                       '<input type="checkbox" name="question'+question_n+'"/> <span class="new_checkbox_answer">answer 2</span>'+'<br/>'+
                       '<button class="add_new_checkbox_answer_btn">Add new answer</button>');
    };

    var add_new_checkbox_answer = function()
    {
        var question_n = $(this).closest(".new_question").attr("id");
        var answers = $(this).closest(".new_question").children(".new_question_answers");
        answers.children(".add_new_checkbox_answer_btn").remove();
        answers.append('<input type="checkbox" name="question'+question_n+'"/> <span class="new_checkbox_answer">New answer</span>'+'<br/>'+
                       '<button class="add_new_checkbox_answer_btn">Add new answer</button>');
    }

    var deleteQuestion_btn_clicked = function()
    {
        question_num = 0;
        $(this).closest(".new_question").remove();
        var questions = $(".exam_creator").children(".new_question");
        questions.each(function(){
            $(this).attr("id", ++question_num);
            $(this).children("#question_number_c").text(question_num+")")
            var answers = $(this).children(".new_question_answers").children();
            answers.each(function(){
                if($(this).attr("type") == "radio" || $(this).attr("type") == "checkbox")
                {
                    $(this).attr("name", "question"+question_num);
                }
            });
        });
    }

    var add_new_question = function()
    {
        question_num++;
        $(".exam_creator").append('<div class="new_question" id="'+question_num+'">'+
        '<button class="question_options_btn"></button>'+
        '<div class="question_dropdown_menu">'+
        '<span class="question_menu_arrow-up"></span>'+
        '<button class="textarea_answer_btn">textarea answer</button>'+
        '<button class="radio_answers_btn">Radio answers</button>'+
        '<button class="checkbox_answers_btn">Checkbox answers</button>'+
        '<button class="delete_question_btn">Delete question</button>'+
        '</div>'+
        '<span id="question_number_c" style="font-weight: bold;">'+question_num+')</span> <span class="new_question_content">'+
        'New question added'+
        '</span>'+
        '<div class="new_question_answers">'+
        '<textarea type="textarea" id="answer_textarea" placeholder="Enter answer here"/>'+
        '</div>'+
        '</div>');
    }

    //start with one question
    add_new_question();

    $("#add_new_question_btn").click(add_new_question);

    var exam_id;
    var question_id;

    $("#save_exam_btn").click(function(){

        var exam_name = $(".ce_exam_name").text();

        $.ajax({
            url: "/AJAX/insert_exam.php",
            cache: false,
            type: "post",
            data: {name: exam_name},
            beforeSend: function(){
                $('#loading').html('<img src="/images/loading.gif" width= 30px>');
            },
            success:function(rt){
                exam_id = rt;
                insert_questions();
                $('#loading').html('');
                $('#page_content').load("/AJAX/exam_details.php?exam_id="+exam_id);
                history.pushState({}, null, "/pages/exam_details.php?exam_id="+exam_id);
            }
        });

    });

    function insert_questions()
    {
        $(".new_question").each(function(index){
            var question_content = $(this).children(".new_question_content").text();
            var question_type = $(this).children(".new_question_answers").children().attr("type");
            switch(question_type)
            {
                case "textarea":
                    question_type = 0;
                    break;
                case "radio":
                    question_type = 1;
                    break;
                case "checkbox":
                    question_type = 2;
                    break;
            }
            var answers = $(this).children(".new_question_answers").children();
            var correct_answers = "";
            answers.each(function(){
                if($(this).prop("checked"))
                {
                    correct_answers += $(this).next("span").text()+', ';
                }
                else if($(this).attr("type") == "textarea")
                {
                    correct_answers = $(this).val();
                }
            });
            $.ajax({
                url: "/AJAX/insert_question.php",
                cache: false,
                type: "post",
                data: {content: question_content, correct: correct_answers, type: question_type, exam_id: exam_id},
                beforeSend: function(){
                    $('#loading').html('<img src="/images/loading.gif" width= 30px>');
                },
                success:function(rt){
                    question_id = rt;
                    insert_answers(answers);
                    $('#loading').html('');
                }
            });
        });
    }

    function insert_answers(answers)
    {
        answers.each(function(){
            if($(this).attr("type") == "radio" || $(this).attr("type") == "checkbox")
            {
                var answer_content = $(this).next("span").text();
                $.ajax({
                    url: "/AJAX/insert_answer.php",
                    cache: false,
                    type: "post",
                    data: {content: answer_content, exam_id: exam_id, question_id: question_id},
                    beforeSend: function(){
                        $('#loading').html('<img src="/images/loading.gif" width= 30px>');
                    },
                    success:function(rt){
                        $('#loading').html('');
                    }
                });
            }
        });
    }

    $(".exam_creator").on("dblclick", ".ce_exam_name", switchToInput);
    $(".exam_creator").on("dblclick", ".new_question_content", switchToInput);
    $(".exam_creator").on("click", ".question_options_btn", openQuestionMenu);
    $(".exam_creator").on("mousedown", ".textarea_answer_btn", changeToTextarea_btn_clicked);
    $(".exam_creator").on("mousedown", ".radio_answers_btn", changeToRadio_btn_clicked);
    $(".exam_creator").on("mousedown", ".checkbox_answers_btn", changeToCheckbox_btn_clicked);
    $(".exam_creator").on("mousedown", ".delete_question_btn", deleteQuestion_btn_clicked);
    $(".exam_creator").on("dblclick", ".new_radio_answer", switchToInput);
    $(".exam_creator").on("dblclick", ".new_checkbox_answer", switchToInput);
    $(".exam_creator").on("click", ".add_new_radio_answer_btn", add_new_radio_answer);
    $(".exam_creator").on("click", ".add_new_checkbox_answer_btn", add_new_checkbox_answer);

});
</script>