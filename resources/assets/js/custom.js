
onclickSubmit = function(id) {
    $('#loadingDiv').css('display', 'block');
    $('#'+id).submit();
}

onclickSubmitAction = function(id, action) {
    $('#loadingDiv').css('display', 'block');
    console.log(id + " " + action);
    $('#'+id).attr('action', action);
    $('#'+id).submit();
}

$(function() {
    console.log("filling fulltextarea");
    $('#fulltextarea').each(function() {
        $(this).height($(this).prop('scrollHeight'));
    });
});