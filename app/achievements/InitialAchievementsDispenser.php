<?php


namespace app\achievements;


use app\services\UserUpdate;

class InitialAchievementsDispenser
{
	protected int $userID;
	protected UserUpdate $update;

	public function __construct($wpId)
	{
		$this->userID = $wpId;
		$this->update = new UserUpdate($this->userID);
	}

	public function updateUser()
	{
		if (!$this->update->isNeedUpdate()) return false;

	}
}