<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/wp-load.php';
require_once 'helpers.php';
use app\models\AchievementsData;

$achievementsData = new AchievementsData();
/*
 * add achievement
 */
if (isset($_POST) && $_POST['action'] === 'add_achievement') {
	$uploadDir = $_SERVER['DOCUMENT_ROOT'] . '/wp-content/plugins/achievements/app/src/uploads/';
	$fileName = rand(0, 100) . basename($_FILES['ac_image']['name']);
	$uploadDir = $uploadDir . $fileName;

	if (move_uploaded_file($_FILES['ac_image']['tmp_name'], $uploadDir)) {
		$achievementsData->addAchievement($_POST['ac_title'], $_POST['ac_description'], $fileName);

		reload();
	}
}
/*
 * edit achievement
 */
if (isset($_POST) && $_POST['action'] === 'edit_achievement') {
	$uploadDir = '';

	if (isset($_FILES['ac_edit_image']) && !empty($_FILES['ac_edit_image']['name'])) {
		$uploadDir = $_SERVER['DOCUMENT_ROOT'] . '/wp-content/plugins/achievements/app/src/uploads/';
		$fileName = rand(0, 100) . basename($_FILES['ac_edit_image']['name']);
		$uploadDir = $uploadDir . $fileName;

		if (move_uploaded_file($_FILES['ac_edit_image']['tmp_name'], $uploadDir)) {
			$achievementsData->editAchievement($_POST['ac_edit_title'], $_POST['ac_edit_description'], $fileName, $_POST['ac_id']);

			reload();
		}
	} else {
		$achievementsData->editAchievement($_POST['ac_edit_title'], $_POST['ac_edit_description'], null, $_POST['ac_id']);

		reload();
	}
}
/*
 * delete achievement
 */
if (isset($_POST) && $_POST['action'] === 'delete_achievement') {
	$achievementsData->deleteAchievementById($_POST['id'], $_POST['file_name']);
}

if (isset($_POST) && $_POST['action'] === 'delete_user_achievement') {
	$achievementsData->deleteUserAchievementById($_POST['id']);
}
/*
 * achievements search
 */
if (isset($_POST) && isset($_POST['wp_user_id']) && (!empty($_POST['wp_user_id'])) && (isset($_POST['action'])) === 'ac_search') {
	$achievementsData->getUserAchievementsDataByFlag($_POST['wp_user_id'], 'wp_user_id');
} else if (isset($_POST['user_name']) && (!empty($_POST['user_name']))) {
	$achievementsData->getUserAchievementsDataByFlag($_POST['user_name'], 'user_name');
} else if (isset($_POST['user_surname']) && (!empty($_POST['user_surname']))) {
	$achievementsData->getUserAchievementsDataByFlag($_POST['user_surname'], 'user_surname');
} else if (isset($_POST['ac_id']) && (!empty($_POST['ac_id']))) {
	$achievementsData->getUserAchievementsDataByFlag($_POST['ac_id'], 'ac_id');
}

if (isset($_POST) && $_POST['action'] === 'search_users') {
	searchUsers($_POST['string']);
}

if (isset($_POST) && $_POST['action'] === 'add_achievements_to_users') {
	unset($_POST['action']);
	if (empty($_POST['users'])) {
		echo 'Выберите хотя бы одного пользователя!';

		return false;
	} else if (empty($_POST['achievements'])) {
		echo 'Выберите хотя бы однодостижение!';

		return false;
	}

	addAchievementsToUsers($_POST);
}
