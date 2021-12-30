<?php


namespace app\achievements;


class InitialAchievements
{
	public function getOldtimerState()
	{
		return $settings = [
			'title' => 'Старожил',
			'days' => 800,
			'days_form' => 'дней',
		];
	}
}