<?php

namespace app\modules\settings\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;
use app\modules\settings\models\LoginForm;
use app\modules\settings\Module;

class SiteController extends Controller
{
	public $layout = 'main_site';
	
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@', '?'],
                    ],
                ],
            ],
        ];
    }

	public function actionLogin()
    {
		$user = Yii::$app->controller->module->userModule->get('user');
		
        if ( ! $user->isGuest) {
            return $this->redirect(\yii\helpers\Url::toRoute('default/index'));
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        }
        return $this->render('login', [
            'model' => $model,
        ]);
    }

    public function actionLogout()
    {
		$user = Yii::$app->controller->module->userModule->get('user');
        $user->logout();
        return $this->redirect(Yii::$app->controller->module->id);
    }
}
