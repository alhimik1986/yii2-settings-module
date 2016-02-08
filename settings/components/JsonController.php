<?php
/**
 * 
 * @author Сидорович Николай <sidorovich21101986@mail.ru>
 * @link https://github.com/alhimik1986
 * @copyright Copyright &copy; 2016
 */

namespace app\modules\settings\components;
use yii\web\Controller;

class JsonController extends Controller
{
	/**
	 *  Проверить ошибоки валидации и вывести в формате JSON: если успех - удачно записанные данные, если ошибка - ошибки.
	 */
	public function checkErrorsAndDisplayResult($model)
	{
		if(\Yii::$app->request->isAjax) {
			$messages = array();
			foreach(\Yii::$app->session->getAllFlashes() as $type=>$message)
				$messages[][$type] = $message;

			if ($model->hasErrors()) {
				echo json_encode(array(
					'status'   => 'error',
					'content'  => array($model::className() => $model->getErrors()),
					'messages' => $messages,
				));
			} else {
				echo json_encode(array(
					'status'   => 'success',
					'content'  => array($model::className() => $model->attributes),
					'messages' => $messages,
				));
			}
		} else {
			if ($model->hasErrors()) {
				foreach($model->getErrors() as $key=>$errors) {
					foreach($errors as $error) {
						\Yii::$app->session->setFlash('error', $model->getAttributeLabel($key).': '.$error);
					}
				}
			}
			$this->redirect(\Yii::$app->request->getReferrer());
		}
	}


	/**
	 * Вывести данные в формате JSON
	 */
	public function renderJson($view, $params=array())
	{
		if(\Yii::$app->request->isAjax) {
			$messages = array();
			$content = $this->renderPartial($view, $params, true);
			foreach(\Yii::$app->session->getAllFlashes() as $type=>$message)
				$messages[][$type] = $message;

			echo json_encode(array(
				'status'   => 'success',
				'content'  => $content,
				'messages' => $messages,
			));
		} else {
			$this->renderPartial($view, $params);
		}
	}
}