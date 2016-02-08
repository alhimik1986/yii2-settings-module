# yii2-settings-module
Модуль для хранения и получения системных настроек приложения.

Для чего нужен:
В системных настройках можно хранить параметры подключения к базе данных и менять их через веб-интерфейс. Это удобно. Особенно это бывает необходимо, если код обфусцирован и в нем нельзя задавать настройки. Конечно, можно вынести настройки в отдельный файл, но пользователям вряд ли понравится искать этот файл в дебрях папок и что-то править, согласно дурацким правилам json-, xml-, yaml- или ini-формата. Куда интереснее это будет править настройки через красивый веб-интерфейс.

Обычно хранить натройки в базе данных не очень удобно. Они могут быть одной строчкой, а могут быть массивом.


УСТАНОВКА:

Создать папку modules в папке приложения и поместить туда папку "settings".
В файле config/web.php прописать настройки:

$config = [
    'components' => [
		...........
		'settings' => ['class' => 'app\modules\settings\components\Settings'], // Для доступа к настройкам
    ],
	
	// Для доступа на странцу редактирования настроек
	'modules' => [
		...........
		'settings' => [
			'class' => 'app\modules\settings\Module',
			// Необязательные параметры
			'password' => '123', // Пароль для в входа на страницу редактирования настроек. По умолчанию 123, если указать пустой, то вход без авторизации
			'password_in_settings' => false, // Если указать true, то брать и проверять пароль в настройках (settings.json), а не в web.config-файле.
			'allowedIPs' => ['127.0.0.1', '::1'], // Доступ по IP-адресам
		],
	],
];

// Или так:
$config['components']['settings']['class'] = 'app\modules\settings\components\Settings'; // Для доступа к настройкам
$config['modules']['settings']['class']    = 'app\modules\settings\Module';              // Для доступа на странцу редактирования настроек


Адрес для входа в настройки:
http://localhost/index.php?r=settings
Пароль: 123

Все эти настройки, находится в файле settings/settings.json


Пример доступа к настройкам:
$db = Yii::$app->settings->param['db'];

В переменной $db ключи value, label, description исключены.
Берется только name (ключ настройки) и значение value (значенние настройки).
Т.е., если в settiings.json было:
{
	"db": {
		"value": {
			"connectionString": "sqlite:..\/..\/application\/data\/database.s3db",
			"username": "",
			"password": "",
			"tablePrefix": "",
			"class": "yii\\db\\Connection"
		},
		"label": "Настройки подключения к базе данных.",
		"description": ""
	},
}
То $db будет равен:
[
	'connectionString' => 'sqlite:..\/..\/application\/data\/database.s3db',
	'username'         => '',
	'password'         => '',
	'tablePrefix'      => '',
	'class'            => 'yii\db\Connection',
]


Как можно менять настройки:

use app\modules\settings\models\SettingsModel;

$setting_name = 'db';
$data = [
	'SettingsModel' => [
		'value' => [
			'connectionString' => 'sqlite:..\/..\/application\/data\/database.s3db',
			'username'         => '',
			'password'         => '',
			'tablePrefix'      => '',
			'class'            => 'yii\db\Connection',
		],
	],
];
$model = SettingsModel::getModel($setting_name);
$model->setAttrAndSave($data);
