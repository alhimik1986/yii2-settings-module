<?php

namespace app\modules\settings;
use yii\web\AssetBundle;

class IEAsset extends AssetBundle
{
    public $sourcePath = '@app/modules/settings/assets';
    public $css = [
		'css/ie.css',
    ];
    public $cssOptions = [
		'position' => \yii\web\View::POS_HEAD,
		'condition' => 'lte IE8'
	];
}

