<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use alhimik1986\yii2_settings_module\Module;

alhimik1986\yii2_settings_module\AppAsset::register($this);

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
    NavBar::begin();
    echo Nav::widget([
        'options' => ['class' => 'navbar-nav navbar-right'],
        'items' => [
            ['label' => Module::t('app', 'Home'), 'url' => [Yii::$app->homeUrl]],
            ['label' => Module::t('app', 'Settings'), 'url' => ['/settings']],
           $user->isGuest ?
                ['label' => Module::t('app', 'Login'), 'url' => ['site/login']] :
                [
                    'label' => Module::t('app', 'Logout').' (' . $user->identity->username . ')',
                    'url' => ['site/logout'],
                ],
        ],
    ]);
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
