<?php

namespace alhimik1986\yii2_settings_module\models;
use Yii;

class User extends \yii\base\Object implements \yii\web\IdentityInterface
{
	const DEFAULT_USERNAME = 'yiier';
    public $id;
    public $username=self::DEFAULT_USERNAME;
    public $password;
    public $authKey;
    public $accessToken;

    private static function users()
	{
		if (Yii::$app->controller->module->password_in_settings) {
			$password = SettingsModel::getSetting('password');
		} else {
			$password = Yii::$app->controller->module->password;
		}
		return [
			'1001' => [
				'id' => '1001',
				'username' => self::DEFAULT_USERNAME,
				'password' => $password,
				'authKey' => 'test100key1',
				'accessToken' => '100-token1',
			],
		];
    }

    /**
     * @inheritdoc
     */
    public static function findIdentity($id)
    {
		$users = self::users();
        return isset($users[$id]) ? new static($users[$id]) : null;
    }

    /**
     * @inheritdoc
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
		$users = self::users();
        foreach ($users as $user) {
            if ($user['accessToken'] === $token) {
                return new static($user);
            }
        }

        return null;
    }

    /**
     * Finds user by username
     *
     * @param  string      $username
     * @return static|null
     */
    public static function findByUsername($username=self::DEFAULT_USERNAME)
    {
		$users = self::users();
        foreach ($users as $user) {
            if (strcasecmp($user['username'], $username) === 0) {
                return new static($user);
            }
        }

        return null;
    }

    /**
     * @inheritdoc
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @inheritdoc
     */
    public function getAuthKey()
    {
        return $this->authKey;
    }

    /**
     * @inheritdoc
     */
    public function validateAuthKey($authKey)
    {
        return $this->authKey === $authKey;
    }

    /**
     * Validates password
     *
     * @param  string  $password password to validate
     * @return boolean if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return $this->password === $password;
    }
}
