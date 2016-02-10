<?php

namespace alhimik1986\yii2_settings_module;
use yii\web\AssetBundle;

class IEAsset extends AssetBundle
{
    public $sourcePath ='@vendor/alhimik1986/yii2_settings_module/assets';
    public $css = [
		'css/ie.css',
    ];
    public $cssOptions = [
		'position' => \yii\web\View::POS_HEAD,
		'condition' => 'lte IE8'
	];
}

