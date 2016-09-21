<?php

namespace alhimik1986\yii2_settings_module;
use yii\web\AssetBundle;

class SettingsAsset extends AssetBundle
{
    public $sourcePath ='@vendor/alhimik1986/yii2_settings_module/assets';
	
    public $css = [
		'css/style.css',
		'css/form.css',
		'css/table.css',
		'css/menu.css',
    ];
    public $js = [
    	//'js/jquery/jquery-1.11.2.min.js',
		'js/ajaxForm/ajax-form.js',
		'js/drag/jquery.event.drag-2.2.js',
		'js/drag/jquery.event.drag.live-2.2.js',
		'js/noty/jquery.noty.js',
		'js/noty/layouts/top.js',
		'js/noty/themes/default.js',
    ];
    public $depends = [
		'yii\web\JqueryAsset',
	];
}
