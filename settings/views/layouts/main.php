<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use app\assets\AppAsset;
use app\modules\settings\Module;

app\modules\settings\SettingsAsset::register($this);
app\modules\settings\DataTablesAsset::register($this);
app\modules\settings\IEAsset::register($this);

$user = Yii::$app->controller->module->userModule->get('user');
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>

<div class="wrap">
    <?php
    NavBar::begin([
        'options' => [
            'class' => 'user-menu',
        ],
    ]);
	$items = [];
	$items[] = ['label' => Module::t('app', 'Home'), 'url'   => Yii::$app->homeUrl];
	if ($user->isGuest AND Yii::$app->controller->module->is_password_required()) {
		$items[] = ['label' => Module::t('app', 'Login'), 'url' => ['site/login']];
	}
	if ( ! $user->isGuest)
		$items[] = [
			'label' => Module::t('app', 'Logout').' (' . $user->identity->username . ')',
			'url' => ['site/logout'],
			'linkOptions' => ['data-method' => 'post']
		];
    echo Nav::widget(['items' => $items]);
    NavBar::end();
    ?>
	
	<!-- Вывод flash-сообщений -->
	<?php foreach(Yii::$app->getSession()->getAllFlashes() as $key => $message) {
			echo '<div class="flash-' . $key . '">' . $message . "</div>\n";
		} ?>

	<!-- Ошибки -->
	<div id="error"></div>

    <div class="container" style="margin: 20px;">
        <?= $content ?>
    </div>
</div>

<footer class="footer">
    <div class="container">
    </div>
</footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
