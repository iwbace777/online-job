<script>
    $(document).ready(function() {
        $("button#js-btn-buy").click(function() {
            $.ajax({
                url: "{{ URL::route('async.connection.purchase') }}",
                dataType : "json",
                type : "POST",
                data : { package_id : $(this).attr('data-id') },
                success : function(data){
                    $("form#js-frm-payment").find("input[name='amount']").val(data.amount);
                    $("form#js-frm-payment").find("input[name='invoice']").val(data.invoice);
                    $("form#js-frm-payment").submit();
                }
            });
        });
    });
</script>