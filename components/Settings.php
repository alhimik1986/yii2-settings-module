<?php
/**
 * 
 * @author Сидорович Николай <sidorovich21101986@mail.ru>
 * @link https://github.com/alhimik1986
 * @copyright Copyright (c) 2016 alhimik1986
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace alhimik1986\yii2_settings_module\components;

use alhimik1986\yii2_settings_module\models\SettingsModel;

class Settings extends \yii\base\Component
{
	/**
	 * Параметры настройки.
	 */
	public $param;


	public function __construct()
	{
		$settings = SettingsModel::getSettings();
		foreach($settings as $key=>$value)
			$settings[$key] = $value['value'];
		
		$this->param = $settings;
	}

	public function get($name)
	{
		return SettingsModel::getSetting($name);
	}

	public function set($name, $value)
	{
		return SettingsModel::setSetting($name, $value);
	}
}