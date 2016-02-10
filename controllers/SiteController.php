<?php

namespace alhimik1986\yii2_settings_module\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\helpers\Url;
use alhimik1986\yii2_settings_module\models\LoginForm;
use alhimik1986\yii2_settings_module\Module;

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
            return $this->redirect(Url::toRoute('default/index'));
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
        return $this->redirect(Yii::$app->homeUrl);
    }
}
