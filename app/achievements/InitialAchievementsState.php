<?php

namespace app\achievements;


class InitialAchievementsState
{
	private InitialAchievements $acNames;

	public function __construct()
	{
		$this->acNames = new InitialAchievements();

		$this->setDefaultState();
	}

	protected function setDefaultState()
	{
		$this->oldtimer();
	}

	protected function oldtimer()
	{
		global $wpdb;

		$oldTimerDefs = $this->acNames->getOldtimerState();
		$value = serialize(['days' => $oldTimerDefs['days'], 'days_form' => $oldTimerDefs['days_form']]);

		$wpdb->insert('ac_achievements', [
			'title' => $oldTimerDefs['title'],
			'description' => "Больше {$oldTimerDefs['days']} {$oldTimerDefs['days_form']} в клубе!",
			'file_name' => '',
			'is_default' => true
		]);

		$wpdb->insert('ac_service', [
			'parameter' => $oldTimerDefs['title'],
			'value' => $value,
			'bool_value' => true,
		]);
	}
}