<?php

namespace alhimik1986\yii2_settings_module;
use Yii;
use yii\di\Container;
use yii\web\ForbiddenHttpException;
use alhimik1986\yii2_settings_module\models\SettingsModel;

class Module extends \yii\base\Module
{
    public $controllerNamespace = 'alhimik1986\yii2_settings_module\controllers';
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
            'identityClass' => __NAMESPACE__.'\models\User',
            'enableAutoLogin' => true,
			'class' => 'yii\web\User',
			'loginUrl' => [$this->id.'/site/login'],
		]);
    }
	protected static function namespace_str()
	{
		return str_replace('\\', '/', __NAMESPACE__);
	}
	
    public function registerTranslations()
    {
        Yii::$app->i18n->translations[self::namespace_str().'/*'] = [
            'class' => 'yii\i18n\PhpMessageSource',
            'sourceLanguage' => 'en-US',
            'basePath' => __DIR__ .'/messages',
            'fileMap' => [
                self::namespace_str().'/app' => 'messages.php',
                self::namespace_str().'/settings' => 'settings_messages.php',
            ],
        ];
    }

	/**
	 * Использование интернационализации (перевода) внутри модуля.
	 * Пример использования: use alhimik1986\yii2-settings-module\Module; Module::t('app', 'The data is not found!');
	 */
    public static function t($category, $message, $params = [], $language = null)
    {
        return Yii::t(self::namespace_str() .'/'. $category, $message, $params, $language);
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
