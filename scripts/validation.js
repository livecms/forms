$.validator.setDefaults({ 
    ignore: ":hidden:not(.need-validation)",
    // any other default options and/or rules
});

$('form').each(function () {
    $(this).data('validation', $(this).validate());
    $(this).data('identifier', id = $(this).find(':input').map(function () {
        return $(this).attr('id');
    }).get().join('.'));
    $('<input type="hidden" name="_identifier" value="'+id+'">').insertAfter($(this).find('[name=_token]'));
});