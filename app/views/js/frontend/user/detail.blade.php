<script>
function isNumeric(input){
    var RE = /^-{0,1}\d*\.{0,1}\d+$/;
    return (RE.test(input));
} 
$(document).ready(function() {
    $("input#js-number-score").rating();    
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