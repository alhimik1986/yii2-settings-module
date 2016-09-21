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
class App2Asset extends AssetBundle
{
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
