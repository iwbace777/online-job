<script>
$(document).ready(function() {
    $("input[name='vat_id']").blur(function() {
        $.ajax({
            url: "{{ URL::route('async.user.loadBusiness') }}",
            dataType : "json",
            type : "POST",
            data : { vat_id : $(this).val() },
            success : function(data){
                if (data.result == "success") {
                    $("input[name='email']").val(data.email);
                    $("input[name='name']").val(data.name);
                    $("input[name='phone']").val(data.phone);                    
                } else {
                    $("input[name='email']").val('');
                    $("input[name='name']").val('');
                    $("input[name='phone']").val('');
                }
            }
        });       
    });
    $("select[name='is_business']").change(function() {
        if ($(this).val() == 1) {
            $("div#js-div-business").fadeIn();
        } else {
            $("div#js-div-business").fadeOut();
            $("input[name='vat_id']").val('');
        }
    });

    $("button#js-btn-type").click(function() {
        $("button#js-btn-type").removeClass('green');
        $("button#js-btn-type").addClass('btn-default');
        $(this).addClass('green');
        $(this).removeClass('btn-default');

        if ($(this).attr('data-type') == 1) {
            $("div#js-div-business").fadeIn();
        } else {
            $("div#js-div-business").fadeOut();
            $("input[name='vat_id']").val('');
        }
        $("input[name='is_business']").val($(this).attr('data-type'));
    });
});
function validate() {
    var objList = $("input#js-checkbox-sub-category:checked");
    for (var i = 0; i < objList.length; i++) {
        $("div#js-div-sub-category").append($("<input type='hidden' name='sub_category[]' value=" + objList.eq(i).val() + ">"));
    }
    return true;
}		
</script>