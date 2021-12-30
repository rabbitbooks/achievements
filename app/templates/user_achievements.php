<?php

if (isset($_GET['id']) && !empty($achievements_data_all) && array_key_exists($_GET['id'], $achievements_data_all)) {
?>
    <p class="acc-title">Достижения</p>
    <div class="achievements_container">
    <?php
    define('UPLOADS_URL', plugin_dir_url('achievements') . 'achievements/app/src/uploads/');
    foreach ($achievements_data_all[$_GET['id']]['ac_id'] as $ac_datum) {
        ?>
        <div class="ac_edit_item">
            <div class="ac_img_wrapper">
                <img class="ac_image" src="<?php echo UPLOADS_URL . $ac_datum['file_name'] ?>" alt="Изображение достижения">
            </div>
            <h5 class="ac_edit_title"><?php echo $ac_datum['ac_title'] ?></h5>
            <div class="ac_description"><?php echo $ac_datum['description'] ?></div>
        </div>
        <?php
    }
    ?>
    </div>
<?php
}
