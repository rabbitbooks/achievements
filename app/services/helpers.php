<?php
spl_autoload_register(function ($className) {
	$file = __DIR__ . '/../../' . str_replace('\\','/', $className) . '.php';
	if(file_exists($file)) require_once $file;
});

use app\models\AchievementsData;
use app\controllers\AdminController;
use app\controllers\FrontController;

function reload() {
	header('Location: https://club.convertmonster.ru/wp-admin/admin.php?page=achievements');
}

function searchUsers($string) {
	$data = new AchievementsData();
	$admin = new AdminController();

	$resultArray = $data->searchUserByLastName($string);
	$admin->buildUsersToAddList($resultArray);
}

function addAchievementsToUsers($post) {
	$data = new AchievementsData();

	$data->addAchievementsToUsers($post);
}

add_shortcode( 'achievements', 'renderAchievements' );

function renderAchievements() {
	$front = new FrontController();

	$front->renderUserAchievements();
}