<?php
/**
 * User: alegz
 * Date: 10/27/13
 * Time: 10:02 PM
 *
 * @var string $imageCssPath
 */
?>
jQuery.ajax({
    url: "\/shaytan\/login\/captcha\/refresh\/1",
    dataType: 'json',
cache: false,
    success: function(data) {
    jQuery('<?=$imageCssPath;?>').attr('src', data['url']);
    jQuery('body').data('captcha.hash', [data['hash1'], data['hash2']]);
}
});
