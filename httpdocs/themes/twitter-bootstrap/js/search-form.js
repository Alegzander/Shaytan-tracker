/**
 * User: alegz
 * Date: 11/8/13
 * Time: 3:05 AM
 */

updateAccuracy = function(){

}

processItem = function(className){
    value = parseInt($('input.'+className).val());
    label = $('input.'+className).data('label');

    if (value != 1 && value != 0){
        $('input.'+className).val(1);
        value = 1;
    }

    $('a.'+className).html('');

    if (value == 1){
        $('a.'+className).append('<i class="icon-ok"></i>'+label);
        return label;
    } else if (value == 0){
        $('a.'+className).append('&nbsp;&nbsp;&nbsp;&nbsp;'+label);
        return null;
    } else {
        return null;
    }
}

updateCriteria = function(){
    tagClass = 'search-by-tag';
    nameClass = 'search-by-name';
    tagLabel = processItem(tagClass);
    nameLabel = processItem(nameClass);

    if (tagLabel != null && nameLabel != null){
        label = tagLabel+', '+nameLabel;
    } else if (tagLabel != null){
        label = tagLabel;
    } else if (nameLabel != null){
        label = nameLabel
    } else {
        $('input.'+tagClass).val(1);
        $('input.'+nameClass).val(1);

        updateCriteria();
    }

    $('a.search-options').html(label+'&nbsp;<span class="caret"></span>');
}

updateCriteria();

$('a.search-by-tag').on('click', function(){
    value = parseInt($('input.search-by-tag').val());

    if (value == 1){
        $('input.search-by-tag').val(0);
    } else if (value == 0) {
        $('input.search-by-tag').val(1);
    }

    updateCriteria();
});

$('a.search-by-name').on('click', function(){
    value = parseInt($('input.search-by-name').val());

    if (value == 1){
        $('input.search-by-name').val(0);
    } else if (value == 0) {
        $('input.search-by-name').val(1);
    }

    updateCriteria();
});

$('.search-button').on('click', function(){
    $('form.navbar-search').submit();
});