/**
 * User: alegz
 * Date: 10/15/13
 * Time: 6:18 PM
 */

$('input.description-from-file').on('change', function(event){
    event.preventDefault();

    description = $('textarea.description');

    if ($(this).attr('checked') == 'checked')
        description.prop('disabled', true);
    else
        description.prop('disabled', false);

});