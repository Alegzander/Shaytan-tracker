/**
 * User: alegz
 * Date: 7/22/13
 * Time: 4:14 PM
 */
$(function () {
	$('#srbac_tab a:first').tab('show');
})

$('#srbac_tab a').click(function (e) {
	e.preventDefault();
	$(this).tab('show');
})