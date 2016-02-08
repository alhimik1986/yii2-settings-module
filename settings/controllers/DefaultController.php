<?php
/**
 * 
 * @author Сидорович Николай <sidorovich21101986@mail.ru>
 * @link https://github.com/alhimik1986
 * @copyright Copyright &copy; 2016
 * @package application.modules.settings.controllers
 */

namespace app\modules\settings\controllers;

use Yii;
use yii\filters\AccessControl;
use app\modules\settings\Module;
use app\modules\settings\components\JsonController;
use app\modules\settings\models\SettingsModel;

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