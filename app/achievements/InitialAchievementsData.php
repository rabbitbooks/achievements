<?php


namespace app\achievements;


class InitialAchievementsData
{
	private $wpdb;

	public function __construct() {
		global $wpdb;

		$this->wpdb = $wpdb;
	}

	public function getAcSettingsArray($acTitle)
	{
		$settings = $this->wpdb->get_row("SELECT value, bool_value FROM ac_service WHERE parameter = '{$acTitle}'", ARRAY_A);

		$result = unserialize($settings['value']);
		$result['is_active'] = (bool)$settings['bool_value'];

		return $result;
	}

	public function getServiceDataByUserId($userID)
	{
		return $this->wpdb->get_row("SELECT last_update, bool_value FROM ac_user_data WHERE user_id = {$userID}", ARRAY_A);
	}

	public function insertUser($userID)
	{
		global $wpdb;

		if ($userID == 0) {
			$wpdb->insert('ac_user_data', ['user_id' => $userID, 'last_update' => 0], ['%d', '%d']);
		} else {
			$wpdb->insert('ac_user_data', ['user_id' => $userID, 'last_update' => date('d')], ['%d', '%d']);
		}

	}

	public function updateUser($userID)
	{
		global $wpdb;

		$wpdb->update('ac_user_data', ['last_update' => (int) date('d')], ['user_id' => $userID], ['%d', '%d'], ['%d']);
	}
}