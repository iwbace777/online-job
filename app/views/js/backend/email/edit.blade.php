<script>
$(document).ready(function() {
    $('#js-div-body').html("{{ substr(json_encode($email->body), 1, strlen(json_encode($email->body)) - 2) }}");
    $('#js-div-body').summernote({
        height: 300,
        tabsize: 4,
        toolbar: [
            ['style', ['bold', 'italic', 'underline', 'clear']]
		  ]            
    });
    
});

function validate() {
    $("input[name='body']").val($('#js-div-body').code());
    return true;
}
</script>
