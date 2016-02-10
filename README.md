# yii2-settings-module
Модуль для хранения и получения системных настроек приложения. Является аналогом Yii::$app->params, только его можно править через веб-интерфейс.

<h2>Для чего нужен:</h2>
В системных настройках можно хранить параметры подключения к базе данных и менять их через веб-интерфейс. Это удобно. Особенно это бывает необходимо, если код обфусцирован и в нем нельзя задавать настройки. Конечно, можно вынести настройки в отдельный файл, но пользователям вряд ли понравится искать этот файл в дебрях папок и что-то править, согласно дурацким правилам json-, xml-, yaml- или ini-формата. Куда интереснее это будет править настройки через красивый веб-интерфейс.


<h2>УСТАНОВКА:</h2>

Создать папку modules в папке приложения и поместить туда папку "settings".
В файле config/web.php прописать настройки:
```
$config = [
    'components' => [
		...........
		'settings' => ['class' => 'alhimik1986\yii2_settings_module\components\Settings'], // Для доступа к настройкам
    ],
	
	// Для доступа на странцу редактирования настроек
	'modules' => [
		...........
		'settings' => [
			'class' => 'alhimik1986\yii2_settings_module\Module',
			// Необязательные параметры
			'password' => '123', // Пароль для в входа на страницу редактирования настроек. По умолчанию 123, если указать пустой, то вход без авторизации
			'password_in_settings' => false, // Если указать true, то брать и проверять пароль в настройках (settings.json), а не в web.config-файле.
			'allowedIPs' => ['127.0.0.1', '::1'], // Доступ по IP-адресам
		],
	],
];
```
// Или так:
```
$config['components']['settings']['class'] = 'alhimik1986\yii2_settings_module\components\Settings'; // Для доступа к настройкам
$config['modules']['settings']['class']    = 'alhimik1986\yii2_settings_module\Module';              // Для доступа на странцу редактирования настроек
```

<h2>Адрес для входа в настройки:</h2>
http://localhost/index.php?r=settings
<br>
Пароль: 123

Все эти настройки, находится в файле settings/settings.json


<h2>Пример доступа к настройкам:</h2>
```
$db = Yii::$app->settings->param['db']; // Здесь хранятся кэшированные данные
$db = Yii::$app->settings->get('db');   // То же самое, тольк данные не кэшируются, но быстродействие от этого практически не пострадает
Yii::$app->settings->set('password', '1234243'); // Меняем значение настройки "password"
```
В переменной $db ключи value, label, description исключены, так как они не нужны.
Берется только name (ключ настройки) и значение value (значенние настройки).
Т.е., если в settiings.json было:
```
{
	"db": {
		"value": {
			"connectionString": "sqlite:..\/..\/..\/application\/basic\/data\/database.s3db",
			"username": "",
			"password": "",
			"tablePrefix": "",
			"class": "yii\\db\\Connection"
		},
		"label": "Настройки подключения к базе данных.",
		"description": ""
	},
}
```
То $db будет равен:
```
[
	'connectionString' => 'sqlite:../../../application/data/database.s3db',
	'username'         => '',
	'password'         => '',
	'tablePrefix'      => '',
	'class'            => 'yii\db\Connection',
]
```

<h2>Пример использования этого модуля в файле web.php для установки параметров подключения к базе данных:</h2>
```
require_once(realpath(__DIR__.'/../vendor/alhimik1986/yii2_settings_module/models/SettingsModel.php'));
$config['components']['db'] = alhimik1986\yii2_settings_module\models\SettingsModel::getSetting('db');
```
