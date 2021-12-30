<?php

namespace app\controllers;


use app\view\View;
use app\models\AchievementsData;
use app\achievements\InitialAchievementsSettings;


class AdminController
{
	private View $view;
	private AchievementsData $data;
	private InitialAchievementsSettings $settings;

	public function __construct()
	{
		add_action('add_submenu_mmc',  array($this, 'addItemToMMCPluginMenu'));

		$this->view = new View(__DIR__ . '/../templates');
		$this->data = new AchievementsData();
		$this->settings = new InitialAchievementsSettings();

		$this->enqueue_styles();
	}

	public function addItemToMMCPluginMenu()
	{
		$page = add_submenu_page('new-mmc-plugin', 'Достижения', 'Достижения', 'manage_options', 'achievements', array($this, 'build'));
		add_action( 'load-' . $page, [$this, 'enqueue_styles'] );
	}

	public function enqueue_styles()
	{
//		if ($_GET['page'] != 'achievements') return false;

		wp_enqueue_style('achievements-style', plugins_url('achievements/app/src/achievements-style.css', 'achievements'), [], null);
		wp_enqueue_style('achievements-bootstrap', plugins_url('achievements/app/src/achievements-bootstrap.css', 'achievements'), ['achievements-style'], null);
		wp_enqueue_script('commons', plugins_url('achievements/app/src/commons.js', 'achievements'), ['jquery'], null, true);
	}

	public function build()
	{
		$this->view->renderHtml('admin.php', [
			'page_title' => 'Достижения пользователей',
			'add_achievement' => 'Добавить/редактировать',
			'edit_achievements' => 'Редактирование',
			'achievements' => $this->data->getAllAchievementData(),
			'user_achievement' => 'Достижения пользователей',
			'users_achievements_data' => $this->data->getUserAchievementsDataByFlag(),
			'add_achievement_to_user' => 'Выдать достижение',
			'achievement_settings' => $this->settings->allSettings,
		]);
	}

	public function buildUsersToAddList($users) {
		$this->view->renderHtml('users_list_to_add.php', ['users_list' => $users]);
	}
}