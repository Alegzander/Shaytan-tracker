$(document).ready(function()
{
    $(".collapse").collapse(); //Для менюшки слева
    $(".chzn-select").chosen(); //Для мегакрутых селектов
    $(".chzn-select-with-deselect").chosen({allow_single_deselect:true}); //Для не менее крутых селектов

    //Соц. сети
    $('.social-networks .add-field-button').live('click', function() //Динамическое добавление соц. сетей
    {
        $('.social-networks ul.shaytan-form').append('<li class="item"><select data-placeholder="Социальная сеть" class="chzn-select-with-deselect" tabindex="2"><option>Facebook</option><option>Вконтакте</option><option>Мой мир</option><option>id.uz</option><option>Одноквасники</option><option>Twitter</option></select><input type="text" placeholder="Соц. сеть"><button class="add-field-button"><i class="icon-plus"></i></button><button class="remove-field-button"><i class="icon-minus"></i></button><br/></li>');

        $(".chzn-select-with-deselect").chosen({allow_single_deselect:true});
    });

    $('.social-networks .remove-field-button').live('click', function() //Не менее динамическое удаление соц. сетей
    {
        $(this).parents('li.item').remove();
    });

    //IM
    $('.instant-messengers .add-field-button').live('click', function() //Динамическое добавление соц. сетей
    {
        $('.instant-messengers ul.shaytan-form').append('<li class="item"><select data-placeholder="IM" class="chzn-select-with-deselect" tabindex="2"><option>Jabber</option><option>ICQ</option><option>Skype</option><option>Mail-агент</option><option>Google+</option></select><input type="text" placeholder="IM"><button class="add-field-button"><i class="icon-plus"></i></button><button class="remove-field-button"><i class="icon-minus"></i></button></li>');

        $(".chzn-select-with-deselect").chosen({allow_single_deselect:true});
    });

    $('.instant-messengers .remove-field-button').live('click', function() //Не менее динамическое удаление соц. сетей
    {
        $(this).parents('li.item').remove();
    });
});
