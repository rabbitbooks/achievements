<?php

namespace app\models;


class AchievementsData
{
	public function addAchievement($title, $description, $file_name)
	{
		global $wpdb;

		$data = [
			'title' => $title,
			'description' => $description,
			'file_name' => $file_name
		];

		$wpdb->insert('ac_achievements', $data);
	}

	public function getAllAchievementData()
	{
		global $wpdb;

		return $wpdb->get_results("SELECT * FROM ac_achievements", 'ARRAY_A');
	}

	public function editAchievement($title, $description, $file_name = null, $id)
	{
		global $wpdb;

		$data = [
			'title' => $title,
			'description' => $description,
		];

		if ($file_name != null) $data['file_name'] = $file_name;

		$wpdb->update('ac_achievements', $data, ['id' => $id]);
	}

	public function deleteAchievementById($id, $fileName)
	{
		global $wpdb;

		$fileName = $_SERVER['DOCUMENT_ROOT'] . '/wp-content/plugins/achievements/app/src/uploads/' . basename(parse_url($fileName, PHP_URL_PATH));

		unlink($fileName);
		$wpdb->delete('ac_achievements', ['id' => $id]);
		$wpdb->delete('ac_user_achievements', ['ac_id' => $id]);
	}

	public function getUserAchievementsDataByFlag($parameter = null, $flag = 'all')
	{
		global $wpdb;
		$result = [];

		switch ($flag) {
			case 'all':
				$result = $wpdb->get_results("SELECT * FROM ac_user_achievements", 'ARRAY_A');
				break;
			case 'wp_user_id':
				$result = $wpdb->get_results("SELECT * FROM ac_user_achievements WHERE wp_user_id = {$parameter}", 'ARRAY_A');
				break;
			case 'user_name':
				$result = $wpdb->get_results("SELECT * FROM ac_user_achievements WHERE user_name = {$parameter}", 'ARRAY_A');
				break;
			case 'user_surname':
				$result = $wpdb->get_results("SELECT * FROM ac_user_achievements WHERE user_surname = {$parameter}", 'ARRAY_A');
				break;
			case 'ac_id':
				$result = $wpdb->get_results("SELECT * FROM ac_user_achievements WHERE ac_id = {$parameter}", 'ARRAY_A');
				break;
			default:
				echo 'Ошибка в запросе БД. Передан не верный флаг';
		}

		return $this->prepareUserAchievements($result);
	}

	protected function getAchievementsById($id)
	{
		global $wpdb;

		return $wpdb->get_row("SELECT * FROM ac_achievements WHERE id={$id}", 'ARRAY_A');
	}

	protected function prepareUserAchievements($data): array
	{
		$resultArray = [];

		foreach ($data as $datum) {
			$ac_ids = [];
			foreach ($data as $array) {
				if (array_search($datum['wp_user_id'], $array)) {
					$ac_ids[$array['id']] = $array['ac_id'];
					$resultArray[$datum['wp_user_id']] = [
						'fio' => $array['user_name']. ' ' .$array['user_surname'],
						'ac_id' => $ac_ids
					];
				}
			}
		}

		foreach ($resultArray as &$userArray) {
			foreach ($userArray['ac_id'] as $key => $item) {
				$ac_data = $this->getAchievementsById($item);

				$userArray['ac_id'][$key] = [
					'ac_title' => $ac_data['title'],
					'file_name' => $ac_data['file_name'],
					'description' => $ac_data['description'],
				];
			}
		}

		return $resultArray;
	}

	public function searchUserByLastName($string)
	{
		global $wpdb;

		$sql = ("SELECT first_name, last_name, wp_user_id FROM mm_user_data WHERE LOCATE('{$string}', last_name)");

		return $wpdb->get_results($sql, 'ARRAY_A');
	}

	public function addAchievementsToUsers($data)
	{
		global $wpdb;

		$users = $data['users'];
		$achivs = $data['achievements'];
		$values = '';

		foreach ($users as $user) {
			foreach ($achivs as $achiv) {
				$sql = "SELECT id FROM ac_user_achievements WHERE wp_user_id={$user[0]} AND user_name='{$user[1]}' AND user_surname='{$user[2]}' AND ac_id={$achiv}";
				$result = $wpdb->get_row($sql);

				if ($result == null) {
					$values .= '(';
					$values .= "{$user[0]}, '{$user[1]}', '{$user[2]}', {$achiv}";
					$values .= '), ';
				}
			}
		}

		$values = substr(trim($values), 0, -1);

		$sql = "INSERT INTO `ac_user_achievements` (`wp_user_id`, `user_name`, `user_surname`, `ac_id`) VALUES {$values}";
		$result = $wpdb->query($sql);

		if ($result) {
			echo "Добавлено достижений: {$result}";
		} else {
			echo "Такие достижения уже есть";
		}
	}

	public function deleteUserAchievementById($id)
	{
		global $wpdb;

		$wpdb->delete('ac_user_achievements', ['id' => $id]);
	}
}