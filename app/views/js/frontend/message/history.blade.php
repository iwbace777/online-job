<script>
$(document).ready(function() {
    $("button#js-btn-overview").click(function() {
        if ($(this).parents('tr').eq(0).next().css('display') == 'none') {
            $(this).parents('tr').eq(0).next().fadeIn();
        } else {
            $(this).parents('tr').eq(0).next().fadeOut();
        }
    });    
});
$(function () {
    $('[data-toggle="tooltip"]').tooltip()
});
</script>