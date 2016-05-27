<script>
$(document).ready(function() {    
    $("input#js-number-score").rating();        
    $("button#js-btn-feedback").click(function() {
        if ($("div#js-div-feedback").hasClass('hide')) {
            $("div#js-div-feedback").removeClass('hide');
        } else {
            $("div#js-div-feedback").addClass('hide');
        }        
    });
    
    $("button#js-btn-bid").click(function() {
        if ($("div#js-div-submit").hasClass('hide')) {
            $("div#js-div-submit").removeClass('hide');
        } else {
            $("div#js-div-submit").addClass('hide');
        }        
    });

    $("button#js-btn-proposal").click(function() {
        if ($(this).parents("tr").eq(0).next().css('display') == 'none') {
            $(this).parents("tr").eq(0).next().fadeIn();
        } else {
            $(this).parents("tr").eq(0).next().fadeOut();
        }
    });
    
    $("button#js-btn-message").click(function() {
        var receiverId = $(this).attr('data-receiver');
        var senderId = $(this).attr('data-sender');
        var jobId = $(this).attr('data-job');
         
        bootbox.prompt("{{ trans('job.msg_enter_message') }}", function(result) {                
            if (result === null) {

            } else {
                $.ajax({
                    url: "{{ URL::route('async.message.send') }}",
                    dataType : "json",
                    type : "POST",
                    data : { receiver_id : receiverId, sender_id : senderId, job_id : jobId, message : result },
                    success : function(data){
                        bootbox.alert(data.msg);
                        window.setTimeout(function(){
                            bootbox.hideAll();
                        }, 2000);                            
                        return;
                    }
                });                   
            }
          });
    });
    
    $("button#js-btn-hire").click(function() {
        var bidId = $(this).parents("tr").eq(0).attr('data-id');
        bootbox.confirm("{{ trans('common.are_you_sure') }}", function(result) {
            if (result) {
                $("input[name='bid_id']").val(bidId);
                $("form#js-frm-hire").submit();
            }
        }); 
    });
    
    @if (isset($is_open) && $is_open)
        $("button#js-btn-bid").click();
    @endif    
});

function validate() {
    var description = $("textarea[name='description']").val();
    var price = $("input[name='price']").val();
    if (description == '') {
        bootbox.alert("{{ trans('job.msg_enter_description') }}");
        window.setTimeout(function(){
            bootbox.hideAll();
        }, 2000);           
        return false;
    }
    if (price == '' || String(price) != String(Number(price))) {
        bootbox.alert("{{ trans('job.msg_enter_price') }}");
        window.setTimeout(function(){
            bootbox.hideAll();
        }, 2000);           
        return false;
    }
    return true;
}
$(function () {
    $('[data-toggle="tooltip"]').tooltip()
});
</script>