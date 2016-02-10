<?php
/**
 * 
 * @author Сидорович Николай <sidorovich21101986@mail.ru>
 * @link https://github.com/alhimik1986
 * @copyright Copyright (c) 2016 alhimik1986
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace alhimik1986\yii2_settings_module\controllers;

use Yii;
use yii\filters\AccessControl;
use alhimik1986\yii2_settings_module\Module;
use alhimik1986\yii2_settings_module\components\JsonController;
use alhimik1986\yii2_settings_module\models\SettingsModel;

class DefaultController extends JsonController
{
	public $layout = 'main';

	public function behaviors()
	{
		return [
			'access' => [
				'class' => AccessControl::className(),
				'rules' => [
					[
						'actions' => [],
						'allow' => true,
						'roles' => ['?', '@'],
					],
				],
			]
		];
	}


	/**
	 * Страница настроек.
	 */
	public function actionIndex()
	{
		return $this->render('index', array(
			'data'=>SettingsModel::getSettings(),
			'model' => new SettingsModel,
		));
	}


	/**
	 * Форма создания и редактирования.
	 */
	public function actionForm($id)
	{
		if (Yii::$app->request->isPost) {
			$model = SettingsModel::getModel($id);
			$model->setAttrAndSave(Yii::$app->request->post());
			// Проверить ошибоки валидации и вывести в формате JSON: если успех - удачно записанные данные, если ошибка - ошибки.
			$this->checkErrorsAndDisplayResult($model);
		} else {
			$model = SettingsModel::getModel($id);
			$this->renderJson('_form', array(
				'model'     => $model,
				'formTitle' => $model->name,
			));
		}
	}


	/**
	 * Список всех строк для обновления таблицы.
	 */
	public function actionRows()
	{
		$this->renderJson('_rows', array(
			'data' => SettingsModel::getSettings(),
		));
	}
}