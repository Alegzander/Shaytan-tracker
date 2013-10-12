/**
 * User: alegz
 * Date: 7/8/13
 * Time: 11:51 AM
 */

$(document).on('click', '.check-all', function(){
	inputFields = $('.checkbox-group label input');

	if ($(this).attr('checked')){
		inputFields.each(function(){
			$(this).attr('checked', true);
		});
	} else {
		inputFields.each(function(){
			$(this).attr('checked', false);
		});
	}
});