<?php
/**
 * Для сохранения используется метод $model->setAttrAndSave($data).
 * Данные хранятся в файле ../settings/settings.json
 * 
 * @author Сидорович Николай <sidorovich21101986@mail.ru>
 * @link https://github.com/alhimik1986
 * @copyright Copyright (c) 2016 alhimik1986
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace alhimik1986\yii2_settings_module\models;
use Yii;
use alhimik1986\yii2_settings_module\Module;

class SettingsModel extends \yii\base\Model
{
	public static function className() { return basename(str_replace('\\', '/', __CLASS__)); }
	public $name;          // Имя параметра настройки
	public $value;         // Значение параметра настройки
	public $label;         // Название параметра настройки
	public $description;   // Описание параметра настройки
	
	protected static $custom_settings_json_file_path = null; // Нестандартный путь к файлу settings.json
	public static function set_custom_settings_json_file_path($path) { self::$custom_settings_json_file_path = $path; }

	/**
	 * @return string Путь к файлу настроек.
	 */
	public static function getFilePath() {
		if (self::$custom_settings_json_file_path)
			return self::$custom_settings_json_file_path;
		
		return realpath(__DIR__.'/../settings/settings.json');
	}


	/**
	 * @return array Правила валидации.
	 */
	public function rules() {
		return array(
			array(['name'], 'filter', 'filter'=>'trim'), // Убираю пробелы в начале и в конце во всех полях, кроме массивов.
			array(['name'], 'required'),                 // Эти поля не должны быть пустыми (обязательные поля).
			array(['name',], 'string', 'max'=>255), // Не должны быть больше 255 символов.
			array(['label', 'description'], 'string', 'max'=>40000), // Не должны быть больше 40000 символов.
			array(['value'], 'safe'),  // Помечаю аттрибут безопасным, чтобы можно было присваивать массив с последующим его кодированием в json
		);
	}


	/**
	 * @return array Подписи полей (имя_поля => подпись)
	 */
	public function attributeLabels() 
	{
		return array(
			'name'        => Module::t('app', 'Setting name'),
			'value'       => Module::t('app', 'Setting value'),
			'label'       => Module::t('app', 'Label'),
			'description' => Module::t('app', 'Description'),
		);
	}


	/**
	 * Присваивает аттрибуты, проводит валидацию и сохраняет результат.
	 * Нужна для предотвращения повторяющихся строк.
	 */
	public function setAttrAndSave($data)
	{
		$className = self::className();
		if (empty($data[$className])) {
			$this->addError('name', Module::t('app', 'The data is not received.'));
			return false;
		}

		$this->attributes = $data[$className];
		return ( ! $this->hasErrors() AND $this->validate() AND $this->save());
	}


	/**
	 * Сохранение данных в формате json.
	 * @return boolean Результат сохранения.
	 */
	public function save()
	{
		$settings = self::getJsonSettings();

		$data = $this->attributes;
		unset($data['name']);
		$settings[$this->attributes['name']] = $data;

		file_put_contents(self::getFilePath(), self::prettyPrint(json_encode($settings)));

		return true;
	}


	/**
	 * Чтение файла настроек settings.json
	 * @return array Массив параметров настроек.
	 */
	public static function getJsonSettings()
	{
		return json_decode(file_get_contents(self::getFilePath()), true);
	}


	/**
	 * Получает модель по заданным параметрам поиска.
	 * 
	 * @param string $name имя перемаметра настройки:
	 * @param boolean $throwException Бросать исключение, если запись не найдена.
	 * @return mixed $model
	 * @throws yii\web\HttpException 404 "Параметр настройки не найден!"
	 */
	public static function getModel($name, $throwException=true)
	{
		// Читаю файл настроек: settings.json
		$settings = self::getJsonSettings();
		$model = __CLASS__;
        $model = new $model();
		if ( ! isset($settings[$name])) {
			if ($throwException)
				throw new \yii\web\HttpException('404', Module::t('app', 'Setting name not found!'));
			return null;
		}
		$settings = $settings[$name];
		$settings['name'] = $name;
		$model->attributes = $settings;
		return $model;
	}


	/**
	 * @return array Все настройки.
	 */
	public static function getSettings()
	{
		return self::getJsonSettings();
	}


	/**
	 * @param string $name Имя параметра настройки.
	 * @return array Настройка с заданным именем.
	 */
	public static function getSetting($name)
	{
		$settings = self::getJsonSettings();
		return isset($settings[$name]) ? $settings[$name]['value'] : null;
	}


	/**
	 * @param string $name Имя параметра настройки, который необходимо изменить.
	 * @param mixed $value Значение настройки, которое нужно выставить.
	 * @return boolean Результат сохранения.
	 */
	public static function setSetting($name, $value)
	{
		$className = self::className();
		$model = self::getModel($name, $throwException=false);
		if ($model) {
			$data = [$className => ['value' => $value]];
			return $model->setAttrAndSave($data);
		} else {
			$class = __CLASS__;
			$model = new $class();
			$data = [$className => [
				'name' => $name,
				'value' => $value],
				'label' => '',
				'description' => '',
			];
			$model->name = $name;
			return $model->setAttrAndSave($data);
		}
	}


	/**
	 * Делает красивые отступы в json-строке.
	 * @param string $json Обычная сжатая json-строка
	 * @return string Отформатированная json-строка
	 * @source http://stackoverflow.com/questions/6054033/pretty-printing-json-with-php#answer-9776726
	 */
	public static function prettyPrint( $json )
	{
		$result = '';
		$level = 0;
		$in_quotes = false;
		$in_escape = false;
		$ends_line_level = NULL;
		$json_length = strlen( $json );

		for( $i = 0; $i < $json_length; $i++ ) {
			$char = $json[$i];
			$new_line_level = NULL;
			$post = "";
			if( $ends_line_level !== NULL ) {
				$new_line_level = $ends_line_level;
				$ends_line_level = NULL;
			}
			if ( $in_escape ) {
				$in_escape = false;
			} else if( $char === '"' ) {
				$in_quotes = !$in_quotes;
			} else if( ! $in_quotes ) {
				switch( $char ) {
					case '}': case ']':
						$level--;
						$ends_line_level = NULL;
						$new_line_level = $level;
						break;

					case '{': case '[':
						$level++;
					case ',':
						$ends_line_level = $level;
						break;

					case ':':
						$post = " ";
						break;

					case " ": case "\t": case "\n": case "\r":
						$char = "";
						$ends_line_level = $new_line_level;
						$new_line_level = NULL;
						break;
				}
			} else if ( $char === '\\' ) {
				$in_escape = true;
			}
			if( $new_line_level !== NULL ) {
				$result .= "\n".str_repeat( "\t", $new_line_level );
			}
			$result .= $char.$post;
		}

		return $result;
	}
}