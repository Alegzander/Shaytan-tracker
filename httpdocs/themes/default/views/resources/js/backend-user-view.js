/**
 * Created with JetBrains PhpStorm.
 * User: alegz
 * Date: 12/10/12
 * Time: 6:21 PM
 */
$(document).ready(function()
{
    $('.user-text-search a').on('click', function()
    {
        $(this).parent('div').children('input').val('');
    });
});