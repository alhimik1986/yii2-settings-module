<?php

namespace app\modules\settings;
use Yii;
use yii\di\Container;
use yii\web\ForbiddenHttpException;
use app\modules\settings\models\SettingsModel;

class Module extends \yii\base\Module
{
    public $controllerNamespace = 'app\modules\settings\controllers';
	//public $defaultRoute = 'setting';
	public $allowedIPs = ['127.0.0.1', '::1'];
	public $userModule; // Компонент пользователя (для этого модуля)
	public $password = '123'; // Пароль по умолчанию
	public $password_in_settings = false; // Брать и проверять пароль в настройка (settings.json), а не в web.config-файле

    public function init()
    {
        parent::init();
		$this->registerTranslations();
		$this->userModule = new Container();
		$this->userModule->set('user', [
            'identityClass' => 'app\modules\settings\models\User',
            'enableAutoLogin' => true,
			'class' => 'yii\web\User',
			'loginUrl' => [$this->id.'/site/login'],
		]);
    }
	
    public function registerTranslations()
    {
        Yii::$app->i18n->translations['modules/settings/*'] = [
            'class' => 'yii\i18n\PhpMessageSource',
            'sourceLanguage' => 'en-US',
            'basePath' => '@app/modules/settings/messages',
            'fileMap' => [
                'modules/settings/app' => 'messages.php',
            ],
        ];
    }

	/**
	 * Использование интернационализации (перевода) внутри модуля.
	 * Пример использования: use app\modules\settings; Module::t('app', 'The data is not found!');
	 */
    public static function t($category, $message, $params = [], $language = null)
    {
        return Yii::t('modules/settings/' . $category, $message, $params, $language);
    }


	/**
	 * @inheritdoc
	 */
    public function beforeAction($action)
	{
		if (!parent::beforeAction($action)) {
			return false;
		}

		if (Yii::$app instanceof \yii\web\Application && !$this->checkAccess()) {
			throw new ForbiddenHttpException('You are not allowed to access this page.');
		}
		if ( ! $this->checkUser()) {
			$this->userModule->get('user')->loginRequired();
			return false;
		}

		$this->resetGlobalSettings();
		
		return true;
	}

	/**
	 * Resets potentially incompatible global settings done in app config.
	 */
    protected function resetGlobalSettings()
	{
		if (Yii::$app instanceof \yii\web\Application) {
			Yii::$app->assetManager->bundles = [];
		}
}

	/**
	 * @return boolean whether the module can be accessed by the current user
	 */
	protected function checkAccess()
	{
		$ip = Yii::$app->getRequest()->getUserIP();
		foreach ($this->allowedIPs as $filter) {
			if ($filter === '*' || $filter === $ip || (($pos = strpos($filter, '*')) !== false && !strncmp($ip, $filter, $pos))) {
				return true;
			}
		}
		Yii::warning('Access to Gii is denied due to IP address restriction. The requested IP is ' . $ip, __METHOD__);

		return false;
	}


	protected function checkUser()
	{
		if ( ! $this->is_password_required())
			return true;
		
		$user = $this->userModule->get('user');
		$result = $user->getIsGuest();
		return ! $result;
	}
	
	
	public function is_password_required()
	{
		if (Yii::$app->controller->id == 'site')
			return false;
		if ( ! $this->password_in_settings AND empty($this->password)) {
			return false;
		} else if ($this->password_in_settings) {
			$pass = SettingsModel::getSetting('password');
			if (empty($pass))
				return false;
		}
		
		return true;
	}
}
