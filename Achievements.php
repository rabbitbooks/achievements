<?php
/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              http://club.convertmonster.ru
 * @since             1.0.0
 * @package           achievements
 *
 * @wordpress-plugin
 * Plugin Name:       Достижения пользователей
 * Description:       Плагин для добавления и редактирование достижений пользователей
 * Version:           1.0.0
 * Author:            Dmitriy Polyakov
 * Author URI:        https://t.me/dmitriy_webDev
 * Text Domain:       achievements
 * Domain Path:       /languages
 */

require_once 'app/services/helpers.php';
spl_autoload_register(function ($className) {
	$file = __DIR__ . '/' . str_replace('\\', '/', $className) . '.php';
	if (file_exists($file)) {
		require_once $file;
	}
});

use app\controllers\AdminController;
use app\achievements\InitialAchievementsState;
use app\achievements\InitialAchievementsDispenser;

class Achievements
{
	protected InitialAchievementsDispenser $dispenser;

	public function __construct()
	{
		$this->setDefaultState();

		new AdminController;

		add_action('init', [$this, 'updateUser']);
	}

	public function updateUser()
	{
		if (get_current_user_id() == 0) return false;

		$this->dispenser = new InitialAchievementsDispenser(get_current_user_id());

		$this->dispenser->updateUser();
	}

	public function setDefaultState()
	{
		global $wpdb;

		$sql = "CREATE TABLE IF NOT EXISTS `ac_achievements` 
			(`id` INT NOT NULL AUTO_INCREMENT, 
			`title` VARCHAR(100) NOT NULL, 
    		`description` TEXT NOT NULL, 
    		`file_name` VARCHAR(100) NOT NULL, 
    		`is_default` BOOLEAN NOT NULL DEFAULT FALSE,
    		PRIMARY KEY (`id`)) 
    		ENGINE = InnoDB;";

		$wpdb->query($sql);
		$wpdb->query("ALTER TABLE `ac_achievements` ADD UNIQUE(`title`);");

		$sql = "CREATE TABLE IF NOT EXISTS `ac_user_achievements` 
			(`id` INT NOT NULL AUTO_INCREMENT,
			`wp_user_id` INT NOT NULL,
			`user_name` VARCHAR(100) NOT NULL,
    		`user_surname` VARCHAR(100) NOT NULL,
    		`ac_id` INT NOT NULL,
    		PRIMARY KEY (`id`)) 
    		ENGINE = InnoDB;";

		$wpdb->query($sql);

		$sql = "CREATE TABLE IF NOT EXISTS `ac_service` 
				(`parameter` VARCHAR(256) NOT NULL, 
    			`value` TEXT NOT NULL,
    			`bool_value` BOOLEAN NULL DEFAULT NULL,
    			PRIMARY KEY (`parameter`)) 
    			ENGINE = InnoDB CHARSET=utf8 
    			COLLATE utf8_general_ci;";

		$wpdb->query($sql);

		$sql = "CREATE TABLE IF NOT EXISTS `ac_user_data` 
				(`user_id` INT NOT NULL, 
				`last_update` INT(10) NOT NULL, 
    			`bool_value` BOOLEAN NULL DEFAULT FALSE,
    			PRIMARY KEY (`user_id`))
    			ENGINE = InnoDB CHARSET=utf8 
    			COLLATE utf8_general_ci;";

		$wpdb->query($sql);

		new InitialAchievementsState;
	}
}

$achievement = new Achievements;
