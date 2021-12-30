<?php

namespace app\achievements;


class InitialAchievementsSettings
{
	public array $allSettings;
	private InitialAchievementsData $acData;
	private InitialAchievements $acNames;

	public function __construct()
	{
		$this->acNames = new InitialAchievements();
		$this->acData = new InitialAchievementsData();

		$this->allSettings['oldtimer'] = $this->getOldtimerSettings();
	}

	protected function getOldtimerSettings(): array
	{
		$title = $this->acNames->getOldtimerState()['title'];
		$settings = $this->acData->getAcSettingsArray($title);
		$settings['title'] = $title;

		return $settings;
	}
}