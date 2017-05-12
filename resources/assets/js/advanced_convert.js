

showOptions = function (element) {
    console.log("showOptions: " + element);
    hideAllOptions();
    //setTimeout( function() {
        $('#kh_' + element).popover('toggle');
    //}, 10);
}

hideAllOptions = function() { 
    $("[data-toggle=popover]").popover('hide');
}

hideOptions = function (element) {
    
}

replaceConversion = function (element, newWord, replaceAll=false) {
    oldWord = $('#kh_' + element).html();
    $('#kh_' + element).html(newWord);
    if (replaceAll) {
        // replace succeeding instances of the word
        for(i=element; i<init_advanced.length; i++) {
            console.log(i + ": " + $('#kh_' + i).html());
            if (oldWord.toLowerCase() == $('#kh_' + i).html().toLowerCase()) {
                $('#kh_' + i).html(newWord);
            }
        }
    }
    hideAllOptions();
    setTimeout( function() {
        stripHtmlAndWrite('advanced_conversion_text', 'translated_body');
    }, 1000);
    
}

stripHtmlAndWrite = function (source, destination) {
    
    result = $("#" + source).html().replace(/<(?:.|\n)*?>/gm, '');
    $("#" + destination).html(result);
}

$(function() {
    if (typeof custom_init !== 'undefined') {
        for (i = 0; i < custom_init.length; i++) {
            custom_init[i]();
        }
    }
    stripHtmlAndWrite('advanced_conversion_text', 'translated_body');
    for (i = 0; i < init_advanced.length; i++) {
        init_advanced[i]();
    }
});
