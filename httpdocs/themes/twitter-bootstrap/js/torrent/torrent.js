/**
 * User: alegz
 * Date: 10/15/13
 * Time: 6:18 PM
 */
toggelButton = $('input.description-from-file');

toggelDescription = function(object){
    description = $('textarea.description');

    if ($(object).attr('checked') == 'checked')
        description.prop('disabled', true);
    else
        description.prop('disabled', false);
}

toggelButton.on('change', function(event){
    event.preventDefault();

    description = $('textarea.description');

    if ($(this).attr('checked') == 'checked')
        description.prop('disabled', true);
    else
        description.prop('disabled', false);
});

toggelDescription(toggelButton);