const $edit_achievements = jQuery('.ac_edit_text');
const $ac_delete_text = jQuery('.ac_delete_text');
const ac_ajaxurl = '../wp-content/plugins/achievements/app/services/form_handler.php';
const $ac_overlay = jQuery('.ac_overlay');
const $ac_edit_popup = jQuery('.ac_edit_popup');
const $ac_close_popup = jQuery('.ac_close_popup');
const $ac_user_add = jQuery('.ac_user');
const $ac_users_to_add_ul = jQuery('.ac_users_to_add');
const $ac_users_to_add_ready = jQuery('.ac_users_to_add_ready');
const $ac_add_popup = jQuery('.ac_add_popup');
const $choice_achievement = jQuery('.choice_achievement');
const $ac_add_popup_body = jQuery('.ac_add_popup_body');
const $add_achievements = jQuery('.add_achievements');
const $edit_ac_form = $('.edit_ac_form');

function acClosePopup() {
    $ac_overlay.css({'opacity':'0', 'pointer-events': 'none'});
    $ac_edit_popup.css({'opacity':'0', 'pointer-events': 'none'});
    $ac_add_popup.css({'opacity':'0', 'pointer-events': 'none'});
    $ac_add_popup_body.html('');
}

function acOpenPopup() {
    $ac_overlay.css({'opacity':'1', 'pointer-events': 'all'});
    $ac_edit_popup.css({'opacity':'1', 'pointer-events': 'all'});
}

let formHTML = '';

$edit_achievements.on('click', function() {
    if ($edit_ac_form.find('.ac_pop_title').length) formHTML = $edit_ac_form.html();

    if ($(this).data('acDefault') == true) {
        $edit_ac_form.html('<p style="margin-top:18px;">Изменить достижение можно на вкладе Настройки</p>');
    } else {
        $edit_ac_form.html(formHTML);
        $('.ac_pop_title').val($(this).parent().find('.ac_edit_title').text());
        $('.ac_pop_desc').val($(this).parent().find('.ac_edit_description').text());
        $('.ac_id').val($(this).parent().attr('data-ac-id'));
    }
    acOpenPopup();
});

$ac_close_popup.on('click', function() {
    acClosePopup();
});

$ac_overlay.on('click', function() {
    acClosePopup();
});

jQuery(document).ready( function($) {
    $ac_delete_text.on('click', function() {
        const to_delete = confirm('Действительно хотите удалить достижение? Оно так же будет удалено у всех пользователех.');
        if (!to_delete) return;

        let data = {
            'action': 'delete_achievement',
            'id': $(this).parent().attr('data-ac-id'),
            'file_name': $(this).parent().find('.ac_image').attr('src'),
        };

        jQuery.post(ac_ajaxurl, data, function (response) {
            window.location.href = location.href;
            console.log(response)
        });
    });
});

jQuery(document).ready( function($) {
    $('.ac_user_delete_text').on('click', function() {
        const to_delete = confirm('Действительно хотите удалить достижение пользователя?');
        if (!to_delete) return;

        let data = {
            'action': 'delete_user_achievement',
            'id': $(this).parent().attr('data-ac-id'),
            'user_id': $(this).parent().parent().parent().find('td:first-child').text(),
        };

        jQuery.post(ac_ajaxurl, data, function (response) {
            window.location.href = location.href;
        });
    });
});

function onSuccess(response) {
    $ac_users_to_add_ul.html(response);
}

//search_users
$ac_user_add.on('input', function() {
    let data = {
        'action': 'search_users',
        'string': $(this).val(),
    };

    $ac_users_to_add_ul.html('<li>Поиск...</li>');

    jQuery.post(ac_ajaxurl, data, onSuccess);
});

$(document).on('click touchstart', '.ac_users_to_add li', function() {
    $ac_users_to_add_ready.append($(this)[0]);
});

$(document).on('click touchstart', '.ac_users_to_add_ready li span', function() {
   $(this).parent().remove();
});

$('.ac_choice_item').on('click touchstart', function() {
    if ($(this).attr('data-choiced') === 'false') {
        $(this).css({'background-color':'beige'});
        $(this).attr('data-choiced', 'true');
    } else {
        $(this).attr('data-choiced', 'false');
        $(this).css({'background-color':'initial'});
    }
});

function onAddAchivsSuccess(response) {
    $('.ac_add_result').text(response);
    $add_achievements.text('Добавить');
}

$add_achievements.on('click', function(e) {
    let users = [];
    let achivs = [];

    $('.ac_users_to_add_ready li').each(function(item, element){
        const fio = $(element).text().split(' ', 2);
        users.push([$(element).attr('data-id'), fio[0], fio[1]]);
    });
    $('.ac_choice_wrapper div').each(function(item, element){
        if ($(element).attr('data-choiced') === 'true') {
            achivs.push($(element).attr('data-ac-id'));
        }
    });

    let data = {
        'action': 'add_achievements_to_users',
        'users': users,
        'achievements': achivs,
    };

    $(this).text('Выполняем...');

    jQuery.post(ac_ajaxurl, data, onAddAchivsSuccess);

    e.preventDefault();
});

$('.ac_description').on('click', function() {

});