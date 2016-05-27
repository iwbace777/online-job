<script>
    var name = '';
    @if(Session::get('locale') == 'sk')
        name = 'name2';
    @else
        name = 'name';
    @endif
    $(document).ready(function() {
        $("button#js-btn-category").click(function() {
            $("button#js-btn-category").addClass('btn-default');
            $("button#js-btn-category").removeClass('btn-info');
            $(this).removeClass('btn-default');
            $(this).addClass('btn-info');
            $.ajax({
                url: "{{ URL::route('async.load.subCategories') }}",
                dataType : "json",
                type : "POST",
                data : { category_id : $(this).attr('data-id') },
                success : function(result){
                    $("div#js-div-sub-category").html("");                    
                    $("div#js-div-question-list").html("");
                    var subCategories = result.subCategories;
                    var objClone;
                    objClone = $("div#select-js-div-question-item").clone();
                    objClone.find("h3#js-h3-question-name").text("{{ trans('common.sub_category') }}");
                    for (var j = 0; j < result.subCategories.length; j++) {
                        var objCloneAnswer = $("div#clone-js-div-answer-item").clone();
                        objCloneAnswer.attr("id", "js-div-answer-item");
                        objCloneAnswer.removeClass('hide');
                        objCloneAnswer.find("button#js-btn-answer").attr('data-id', result.subCategories[j].id);
                        objCloneAnswer.find("button#js-btn-answer").text(result.subCategories[j][name]);
                        objCloneAnswer.find("button#js-btn-answer").addClass("btn-choice-" + result.subCategories.length);
                        objClone.find("div#js-div-answer-list").append(objCloneAnswer);

                        var objCloneNote = $("div#clone-js-div-note-item").clone();
                        objCloneNote.attr("id", "js-div-note-item");
                        objCloneNote.find("span#js-span-note-item").text(result.subCategories[j][name]);
                        objClone.find("div#js-div-note-list").append(objCloneNote);
                    }
                    
                    objClone.attr('id', 'js-div-question-item');
                    objClone.removeClass('hide');
                    objClone.attr('data-selectable', 1);
                    objClone.attr('data-multiple', 0);
                    objClone.attr('data-notable', 0);
                    objClone.attr('data-optional', 0);
                    objClone.attr('data-id', -1);
                    $("div#js-div-sub-category").append(objClone);
                }
            });            
            
        });
        var categoryId = "{{ $category_id }}";
        if (categoryId != '') {
            $("button[data-id='" + categoryId + "']").click();
            // $("button#js-btn-category").eq(0).parents("div").eq(0).hide();
        }
    });

    function validate() {
        $("div#js-div-detail").html("");
        $("input[name='category_id']").val($("button#js-btn-category.btn-info").attr('data-id'));
        $("input[name='sub_category_id']").val($("div#js-div-sub-category").find("button#js-btn-answer.btn-info").attr('data-id'));
        if ($("input[name='name']").val() == '') {
            bootbox.alert("{{ trans('job.msg_enter_job_name') }}");
            window.setTimeout(function(){
                bootbox.hideAll();
            }, 2000);               
            return false;
        }        
        if ($("select[name='city_id']").val() == '') {
            bootbox.alert("{{ trans('job.msg_select_city') }}");
            window.setTimeout(function(){
                bootbox.hideAll();
            }, 2000);               
            return false;            
        }
        
            
        var questionList = $("div#js-div-question-list").find("div#js-div-question-item");
        for (var i = 0; i < questionList.length; i++) {
            if (questionList.eq(i).attr('data-selectable') == 1) {
                var answerList = questionList.eq(i).find("div#js-div-answer-list").find("div#js-div-answer-item").find("button#js-btn-answer.btn-info");
                if (answerList.length == 0) {
                    var title = questionList.eq(i).find("h3").text();
                    bootbox.alert("Please select '" + title + "' correctly");
                    window.setTimeout(function(){
                        bootbox.hideAll();
                    }, 2000);                       
                    return false;
                }
                for (var j = 0; j < answerList.length; j++) {
                    var objClone = $("<input type='hidden' name='detail[]'>");
                    var questionId = questionList.eq(i).attr('data-id');                        
                    var answerId = answerList.eq(j).attr('data-id');
                    var answerIndex = questionList.eq(i).find("div#js-div-answer-list").find("div#js-div-answer-item").find("button#js-btn-answer")
                            .index(answerList.eq(j));
                    var note = questionList.eq(i).find('textarea#js-textarea-note-item').eq(answerIndex).val();
                    if (note == undefined) {
                        note = '';
                    }
                    objClone.val(questionId + "|||" + answerId + "|||" + note);
                    $("div#js-div-detail").append(objClone);
                }
            } else {
                if (questionList.eq(i).attr('data-optional') == 0) {
                    if (questionList.eq(i).find("#js-text-answer").val() == '') {
                        var title = questionList.eq(i).find("h3").text();
                        bootbox.alert("Please enter '" + title + "' correctly");
                        window.setTimeout(function(){
                            bootbox.hideAll();
                        }, 2000);   
                        return false;                            
                    }
                }
                var objClone = $("<input type='hidden' name='detail[]'>");
                var questionId = questionList.eq(i).attr('data-id');
                var answerId = '';
                var note = questionList.eq(i).find("#js-text-answer").val();
                objClone.val(questionId + "|||" + answerId + "|||" + note);
                $("div#js-div-detail").append(objClone);                    
            }
        }
        return true;
    }

    function onClickAnswerNote(obj) {
        var note = $(obj).attr('data-note');
        bootbox.prompt({
            title: "Notes",
            value: note,
            callback: function(result) {
                if (result === null) {
                    
                } else {
                    $(obj).attr('data-note', result);
                }
            }
        }); 
    }
    
    function autoScroll(obj) {
        var offset = $(obj).offset(); 
        if ( $(window).scrollTop() + $(window).height() - 300 < offset.top){
            $('html, body').animate({ scrollTop: $(window).scrollTop() + $(window).height() - 250 }, 800);
        }        
    }       
        
    function onClickSubCategory(obj) {
        autoScroll(obj);
        var objParent = $(obj).parents("div#js-div-question-item").eq(0);
        if (objParent.attr("data-id") == '-1') {
            objParent.find("button#js-btn-answer").removeClass('btn-info');
            objParent.find("button#js-btn-answer").addClass('btn-default');
            $(obj).addClass('btn-info');
            $(obj).removeClass('btn-default');
            $.ajax({
                url: "{{ URL::route('async.load.questions') }}",
                dataType : "json",
                type : "POST",
                data : { sub_category_id : $(obj).attr('data-id') },
                success : function(result){
                    $("div#js-div-question-list").html("");
                    var questions = result.questions;
                    for (var i = 0; i < questions.length; i++) {
                        var objClone;
                        if (questions[i].is_selectable) {
                            objClone = $("div#select-js-div-question-item").clone();
                            objClone.find("h3#js-h3-question-name").text(questions[i][name]);
                            for (var j = 0; j < questions[i].answers.length; j++) {
                                var objCloneAnswer = $("div#clone-js-div-answer-item").clone();
                                objCloneAnswer.attr("id", "js-div-answer-item");
                                objCloneAnswer.removeClass('hide');
                                objCloneAnswer.find("button#js-btn-answer").attr('data-id', questions[i].answers[j].id);
                                objCloneAnswer.find("button#js-btn-answer").text(questions[i].answers[j][name]);
                                objCloneAnswer.find("button#js-btn-answer").addClass("btn-choice-" + questions[i].answers.length);
                                objClone.find("div#js-div-answer-list").append(objCloneAnswer);

                                var objCloneNote = $("div#clone-js-div-note-item").clone();
                                objCloneNote.attr("id", "js-div-note-item");
                                objCloneNote.find("span#js-span-note-item").text("{{ trans('job.note_description') }}(" + questions[i].answers[j][name] + ")");
                                objClone.find("div#js-div-note-list").append(objCloneNote);
                            }
                        } else {
                            objClone = $("div#text-js-div-question-item").clone();
                            if (questions[i].is_optional == 0) {
                                objClone.find("h3#js-h3-question-name").text(questions[i][name] + " *");
                            } else {
                                objClone.find("h3#js-h3-question-name").text(questions[i][name]);
                            }
                            var questionName = questions[i]['name'];
                            if (questionName.toLowerCase() == 'description' || questionName.toLowerCase() == 'addition information') {
                                objClone.find("#js-text-answer").remove();
                                objClone.find("div#js-div-question-area").append($('<textarea class="form-control" id="js-text-answer" onfocus="autoScroll(this)" rows="5"></textarea>'));
                            }
                        }
                        objClone.attr('id', 'js-div-question-item');
                        objClone.removeClass('hide');
                        objClone.attr('data-selectable', questions[i].is_selectable);
                        objClone.attr('data-multiple', questions[i].is_multiple);
                        objClone.attr('data-notable', questions[i].is_notable);
                        objClone.attr('data-optional', questions[i].is_optional);
                        objClone.attr('data-id', questions[i].id); 
                        $("div#js-div-question-list").append(objClone); 
                    }
                    $("div#js-div-question-list").append(objClone);

                    var objCloneName = $("div#clone-js-div-name").clone();
                    objCloneName.removeClass('hide');
                    objCloneName.attr("id", "js-div-name");
                    $("div#js-div-question-list").append(objCloneName);                    

                    var objCloneCity = $("div#clone-js-div-city").clone();
                    objCloneCity.removeClass('hide');
                    objCloneCity.attr("id", "js-div-city");
                    $("div#js-div-question-list").append(objCloneCity);                    
                    
                    var objCloneFile = $("div#clone-js-div-file").clone();
                    objCloneFile.removeClass('hide');
                    objCloneFile.attr("id", "js-div-file");
                    $("div#js-div-question-list").append(objCloneFile);

                    var objCloneSubmit = $("div#clone-js-div-submit").clone();
                    objCloneSubmit.removeClass('hide');
                    $("div#js-div-question-list").append(objCloneSubmit);                                                                    
                }
            });              
        } else {        
            var is_selectable = objParent.attr("data-selectable");
            var is_multiple = objParent.attr("data-multiple");
            var is_notable = objParent.attr("data-notable");
            var is_optional = objParent.attr("data-optional");
    
            if (is_multiple == 1) {
                if ($(obj).hasClass('btn-info')) {
                    $(obj).removeClass('btn-info');
                    $(obj).addClass('btn-default');
                } else {
                    $(obj).addClass('btn-info');
                    $(obj).removeClass('btn-default');                    
                }
            } else {
                objParent.find("button#js-btn-answer").removeClass('btn-info');
                objParent.find("button#js-btn-answer").addClass('btn-default');
                $(obj).addClass('btn-info');
                $(obj).removeClass('btn-default');
            }
    
            if (is_notable == 1) {
                var objList = objParent.find("button#js-btn-answer");
                for (var i = 0; i < objList.length; i++) {
                    if (objList.eq(i).hasClass('btn-info')) {
                        objList.eq(i).parents("div#js-div-question-item").eq(0).find("div#js-div-note-item").eq(i).removeClass('hide');
                    } else {
                        objList.eq(i).parents("div#js-div-question-item").eq(0).find("div#js-div-note-item").eq(i).addClass('hide');
                    }
                }
            }
        }
    }
</script>