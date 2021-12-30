<?php
spl_autoload_register(function ($className) {
	$file = __DIR__ . '/../../' . str_replace('\\','/', $className) . '.php';
	if(file_exists($file)) require_once $file;
});

require_once 'helpers.php';

use app\models\AchievementsData;

$achievementsData = new AchievementsData();

if (!isset($_POST['action'])) die('Ошибка! Не передано событие');

//switch ($_POST['action']) {
//	case 'delete_achievements':
//		$achievementsData->deleteAchievement($_POST['id']);
//		break;
//	case 'search_users':
//		searchUsers($_POST['string']);
//		break;
//}

function checkParameter($parameter) {
	if (!isset($_POST[$parameter])) {
		header('HTTP/1.1 500 Internal Server Yourserver');
		header('Content-Type: application/json; charset=UTF-8');
		die(json_encode([
			'errorCode' => 1,
			'message' => "Ошибка! Не передан параметр {$parameter}"])
		);
	} elseif (empty(trim($_POST[$parameter]))) {
		header('HTTP/1.1 500 Internal Server Yourserver');
		header('Content-Type: application/json; charset=UTF-8');
		die(json_encode([
			'errorCode' => 2,
			'message' => "Ошибка! Пустое значение {$parameter}"])
		);
	}
}