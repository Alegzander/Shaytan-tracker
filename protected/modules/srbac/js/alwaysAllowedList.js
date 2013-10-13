/**
 * User: alegz
 * Date: 7/22/13
 * Time: 4:14 PM
 */
$('div.tab-pane label.checkbox').click(function (event) {
    event.preventDefault();
    	checkbox = $(this).children('input[type=checkbox]');

    if (checkbox.attr('checked') !== 'checked')
        checkbox.attr('checked', 'checked');
    else
        checkbox.attr('checked', false);
})