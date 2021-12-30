<?php


namespace app\services;

use app\achievements\InitialAchievementsData;

class UserUpdate
{
	protected int $userID;
	protected InitialAchievementsData $data;

	/**
	 * UserUpdate constructor.
	 * @param $userID
	 */
	public function __construct($userID)
	{
		$this->userID = $userID;
	}

	/**
	 * @return bool
	 */
	public function isNeedUpdate(): bool
	{
		$this->data = new InitialAchievementsData();
		$serviceData = $this->data->getServiceDataByUserId($this->userID);
		var_dump(!$serviceData, $this->userID);
		if (!$serviceData) {
			var_dump('insertUser ' . $this->userID);
			$this->data->insertUser($this->userID);

			return true;
		}

		if ((int) $serviceData['last_update'] < (int) date('d')) {
			$this->data->updateUser($this->userID);
//			var_dump('updateUser ' . $this->userID);
			return true;
		}

		return false;
	}
}