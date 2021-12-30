<?php

namespace app\controllers;


use app\view\View;
use app\models\AchievementsData;


class FrontController
{
	private View $view;
	private AchievementsData $data;

	public function __construct()
	{

		$this->view = new View(__DIR__ . '/../templates');
		$this->data = new AchievementsData();

	}

	public function renderUserAchievements()
	{
		$this->view->renderHtml('user_achievements.php', [
			'achievements_data' => $this->data->getAllAchievementData(),
			'achievements_data_all' => $this->data->getUserAchievementsDataByFlag(),
		]);
	}

}