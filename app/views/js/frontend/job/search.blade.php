<script>
function isNumeric(input){
    var RE = /^-{0,1}\d*\.{0,1}\d+$/;
    return (RE.test(input));
}    
$(document).ready(function() {
    var substringMatcher = function(strs) {
        return function findMatches(q, cb) {
            var matches, substrRegex;
            matches = [];
            substrRegex = new RegExp(q, 'i');
            $.each(strs, function(i, str) {
                if (substrRegex.test(str)) {
                    matches.push({ value: str });
                }
            });
            cb(matches);
        };
    };

    var states = [];
    
    @foreach ($cities as $key => $value)
        states[{{ $key }}] = '{{ $value->name }}';
    @endforeach
       
    $('input#city').typeahead({
      hint: true,
      highlight: true,
      minLength: 1
    }, {
      name: 'states',
      displayKey: 'value',
      source: substringMatcher(states)
    });


    $("button#js-btn-bid").click(function() {
        if ($(this).parents('tr').eq(0).next().next().css('display') == 'none') {
            $(this).parents('tr').eq(0).next().next().fadeIn();
        } else {
            $(this).parents('tr').eq(0).next().next().fadeOut();
        }        
    });

    $("button#js-btn-overview").click(function() {
        if ($(this).parents('tr').eq(0).next().css('display') == 'none') {
            $(this).parents('tr').eq(0).next().fadeIn();
        } else {
            $(this).parents('tr').eq(0).next().fadeOut();
        }
    });

    $("button#js-btn-submit").click(function() {
        var description = $(this).parents("tr").eq(0).find("#js-txt-description").val();
        var price = $(this).parents("tr").eq(0).find("#js-txt-price").val();
        var jobId = $(this).parents("tr").eq(0).attr('data-id');
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
        var objThis = $(this);
        $.ajax({
            url: "{{ URL::route('async.job.doBid') }}",
            dataType : "json",
            type : "POST",
            data : { description : description, price : price, job_id : jobId },
            success : function(data){
                $(this).parents('tr').eq(0).next().fadeOut();
                objThis.parents('tr').eq(0).fadeOut();
                objThis.parents('tr').eq(0).prev().prev().find("button#js-btn-bid").removeClass('blue').addClass('red').text("{{ trans('common.bidded') }}");                
                bootbox.alert(data.msg);
                window.setTimeout(function(){
                    bootbox.hideAll();
                }, 2000);                   
            }
        });
        
    });        
    
});

function validate() {
    $("form#js-frm-search").attr('action', "{{ URL::route('job.search') }}" + "/" + $("#category").val());
    $("form#js-frm-search").submit();
}

$(function () {
    $('[data-toggle="tooltip"]').tooltip();
});
</script>