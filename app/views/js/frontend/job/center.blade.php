<script>
function isNumeric(input){
    var RE = /^-{0,1}\d*\.{0,1}\d+$/;
    return (RE.test(input));
} 
$(document).ready(function() {
    $("button#js-btn-overview").click(function() {
        if ($(this).parents('tr').eq(0).next().css('display') == 'none') {
            $(this).parents('tr').eq(0).next().fadeIn();
        } else {
            $(this).parents('tr').eq(0).next().fadeOut();
        }

        if ($(this).parents('tr').eq(0).next().next().css('display') == 'none') {
            $(this).parents('tr').eq(0).next().next().fadeIn();
        } else {
            $(this).parents('tr').eq(0).next().next().fadeOut();
        }        
    });    

    $("button#js-btn-submit").click(function() {
        var description = $(this).parents("tr").eq(0).find("#js-txt-description").val();
        var price = $(this).parents("tr").eq(0).find("#js-txt-price").val();
        var jobId = $(this).parents("tr").eq(0).attr('data-id');
        var objThis = $(this);
        if (description == '') {
            bootbox.alert("{{ trans('job.msg_enter_description') }}");
            window.setTimeout(function(){
                bootbox.hideAll();
            }, 2000);               
            return;
        }

        if (price == '' || !isNumeric(price)) {
            bootbox.alert("{{ trans('job.msg_enter_price') }}");
            window.setTimeout(function(){
                bootbox.hideAll();
            }, 2000);               
            return;
        }

        $.ajax({
            url: "{{ URL::route('async.job.doBid') }}",
            dataType : "json",
            type : "POST",
            data : { description : description, price : price, job_id : jobId },
            success : function(data){
                if (data.result == 'danger') {
                    bootbox.alert(data.msg);
                    window.setTimeout(function(){
                        window.location.href = "{{ URL::route('connection.purchase') }}";
                    }, 2000);                    
                } else {
                    bootbox.alert(data.msg);
                    window.setTimeout(function(){
                        bootbox.hideAll();
                    }, 2000);
    
                    objThis.parents('tr').eq(0).prev().prev().find("button#js-btn-overview").click();
                                    
                    if (data.result == "success") {
                        objThis.parents('tr').eq(0).prev().prev().find("td").eq(2).html('<span class="label label-danger">Applied</span>');
                    }
                }
            }
        });
        
    });    
});
$(function () {
    $('[data-toggle="tooltip"]').tooltip()
});
</script>