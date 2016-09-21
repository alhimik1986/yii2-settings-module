<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace alhimik1986\yii2_settings_module;

use yii\web\AssetBundle;

/**
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class AppAsset extends AssetBundle
{
	public function init()
	{
		parent::init();
		$this->disableJqueryBootstrap();
	}
	public function disableJqueryBootstrap()
	{
		// resetting BootstrapAsset to not load own css files
		\Yii::$app->assetManager->bundles['yii\\bootstrap\\BootstrapAsset'] = [
			'css' => [],
			'js' => [],
		];
		\Yii::$app->assetManager->bundles['yii\\bootstrap\\BootstrapPluginAsset'] = [
			'css' => [],
			'js' => [],
		];
		\Yii::$app->assetManager->bundles['yii\\web\\JqueryAsset'] = [
			'css' => [],
			'js' => [],
		];
	}

    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'css/site.css',
    ];
    public $js = [
    ];
    public $depends = [
        'alhimik1986\yii2_settings_module\SettingsAsset',
        'alhimik1986\yii2_settings_module\IEAsset',
        'alhimik1986\yii2_settings_module\DataTablesAsset',
    ];
	public $jsOptions = [ 'position' => \yii\web\View::POS_HEAD ];
}
