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

function test($time) {
	global $wpdb;

	$isLastUpdate = $wpdb->get_row("SELECT * FROM `ac_service` WHERE `parameter`='last_update'");

	if ($isLastUpdate == null) {
		$wpdb->insert('ac_service', ['parameter' => 'last_update', 'value' => date("H:i")]);
	} else {
		global $wpdb;

		$wpdb->update('ac_service', ['value' => date("H:i:s")], ['parameter' => 'last_update']);;

//var_dump(date("H:i") > strtotime('08:00'));
		if ( !((date("H:i") > '14:00' && date("H:i") < '19:59') && ($isLastUpdate > '14:00' && $isLastUpdate < '19:59')) ||
			((date("H:i") > '20:00' && date("H:i") < '07:59') && ($isLastUpdate > '20:00' && $isLastUpdate < '07:59')) ||
			((date("H:i") > '14:00' && date("H:i") < '19:59') && ($isLastUpdate > '14:00' && $isLastUpdate < '19:59'))
		) {
			$result = $wpdb->update('ac_service', ['value' => date("H:i")], ['parameter' => 'last_update']);

//				var_dump(date("H:i"));
		}

	}

}