<script>
$(document).ready(function() {
    $("select[name='is_selectable']").change(function() {
        if ($(this).val() == 1) {
            $("select[name='is_multiple']").attr("readonly", false);
            $("select[name='is_notable']").attr("readonly", false);
            $("select[name='is_optional']").attr("readonly", true);

            $("select[name='is_multiple']").val(0);
            $("select[name='is_notable']").val(0);
            $("select[name='is_optional']").val(0);

            onAddAnswer();
        } else {
            $("select[name='is_multiple']").attr("readonly", true);
            $("select[name='is_notable']").attr("readonly", true);
            $("select[name='is_optional']").attr("readonly", false);
            
            $("select[name='is_multiple']").val(0);
            $("select[name='is_notable']").val(0);
            $("select[name='is_optional']").val(0);

            $("#js-answer-list").html("");
        }
    });

    $("select[name='is_selectable']").change();
});
</script>
