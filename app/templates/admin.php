<?php
define('UPLOADS_URL', plugin_dir_url('achievements') . 'achievements/app/src/uploads/');
?>
<h1><?php echo $page_title ?></h1>
<div class="tabs">
    <input type="radio" name="inset" value="" id="tab_1" checked>
    <label for="tab_1"><?php echo $add_achievement ?></label>

    <input type="radio" name="inset" value="" id="tab_2">
    <label for="tab_2"><?php echo $user_achievement ?></label>

    <input type="radio" name="inset" value="" id="tab_3">
    <label for="tab_3"><?php echo $add_achievement_to_user ?></label>

    <div id="txt_1">
        <form action="<?php echo plugins_url('/achievements/app/services/form_handler.php') ?>" method="POST" enctype="multipart/form-data">
            <input required name="ac_title" type="text">
            <br><br>
            <textarea required name="ac_description"></textarea>
            <br>
            <input required name="ac_image" type="file" accept=".jpg, .jpeg, .png">
            <br>
            <input name="action" type="hidden" value="add_achievement">
            <button class="btn btn-primary">Добавить</button>
        </form>
        <br>
        <h5><?php echo $edit_achievements ?></h5>
        <div class="ac_edit_wrapper">
			<?php
			foreach ($achievements as $achievement):
				?>
                <div data-ac-id="<?php echo $achievement['id'] ?>" class="ac_edit_item">
                    <div class="ac_img_wrapper">
                        <img class="ac_image" src="<?php echo UPLOADS_URL . $achievement['file_name'] ?>" alt="Изображение достижения">
                    </div>
                    <h5 class="ac_edit_title"><?php echo $achievement['title'] ?></h5>
                    <p class="ac_edit_description"><?php echo $achievement['description'] ?></p>
                    <div class="ac_edit_item_overlay"></div>
                    <h4 class="ac_edit_text">Изменить</h4>
                    <h4 class="ac_delete_text">Удалить</h4>
                </div>
			<?php
			endforeach;
			?>
        </div>
        <div class="ac_overlay"></div>
        <div class="ac_edit_popup">
            <div class="ac_close_popup">X</div>
            <div class="ac_edit_popup_wrapper">
                <form action="<?php echo plugins_url('/achievements/app/services/form_handler.php') ?>" method="POST" enctype="multipart/form-data">
                    <h3>Изменить достижение</h3>
                    <input required name="ac_edit_title" class="ac_pop_title" type="text">
                    <br><br>
                    <textarea required name="ac_edit_description" class="ac_pop_desc"></textarea>
                    <br>
                    <input name="ac_edit_image" type="file" accept=".jpg, .jpeg, .png">
                    <br><br>
                    <input name="action" type="hidden" value="edit_achievement">
                    <input name="ac_id" type="hidden" class="ac_id">
                    <button class="btn btn-primary">Изменить</button>
                </form>
            </div>
        </div>

    </div>
    <div id="txt_2">
        <div class="ac_user_search">
            <h5>Поиск</h5>
            <form action="<?php echo plugins_url('/achievements/app/services/form_handler.php') ?>" method="POST">
                <input type="hidden" name="action" value="ac_search">
                <label><input type="text" name="wp_user_id" placeholder="WP ID"></label>
                <label><input type="text" name="user_name" placeholder="Имя"></label>
                <label><input type="text" name="user_surname" placeholder="Фамилия"></label>
                <label><input type="text" name="ac_id" placeholder="ID достижения"></label>
                <div class="ac_search_popup">
                    <label><input class="ac_title" type="text" name="ac_title" placeholder="Выбрать достижения"></label>
                </div>
                <button class="btn btn-primary">Поиск</button>
            </form>
        </div>
        <br>
        <table>
            <thead>
                <tr>
                    <th>WP ID</th>
                    <th>Имя Фамилия</th>
                    <th>Достижения</th>
                </tr>
            </thead>
            <tbody>
            <?php
			foreach ($users_achievements_data as $user => $value) {
				?>
                <tr>
                    <td><?php echo $user ?></td>
                    <td><?php echo $value['fio'] ?></td>
                    <td class="ac_user_wrapper">
                        <?php
                        foreach ($value['ac_id'] as $key => $ac_datum) {
                            ?>
                            <div data-user-ac-id="" data-ac-id="<?php echo $key ?>" class="ac_edit_item">
                                <div class="ac_img_wrapper">
                                    <img class="ac_image" src="<?php echo UPLOADS_URL . $ac_datum['file_name'] ?>" alt="Изображение достижения">
                                </div>
                                <h5 class="ac_edit_title"><?php echo $ac_datum['title'] ?></h5>
                                <div class="ac_edit_item_overlay"></div>
                                <h4 class="ac_user_delete_text">Удалить</h4>
                            </div>
                            <?php
                        }
                        ?>
                    </td>
                </tr>
				<?php
			}
			?>
            </tbody>
        </table>
    </div>
    <div id="txt_3">
        <h5>Начните вводить фамилию</h5>
        <label><input type="text" class="ac_user" placeholder="Фамилия"></label>
        <ul class="ac_users_to_add_ready"></ul>
        <ul class="ac_users_to_add"></ul>
        <br>
        <h5>Выбрать достижения</h5>
        <div class="ac_choice_wrapper">
        <?php
            foreach ($achievements as $achievement):
            ?>
            <div data-ac-id="<?php echo $achievement['id'] ?>" data-choiced="false" class="ac_choice_item">
                <div class="ac_img_wrapper">
                    <img class="ac_image" src="<?php echo UPLOADS_URL . $achievement['file_name'] ?>" alt="Изображение достижения">
                </div>
                <h5 class="ac_choice_title"><?php echo $achievement['title'] ?></h5>
                <p class="ac_choice_description"><?php echo $achievement['description'] ?></p>
            </div>
            <?php
            endforeach;
            ?>
        </div>
        <br>
        <button class="btn btn-primary add_achievements">Добавить</button>
        <div class="ac_add_result"></div>
    </div>
</div>