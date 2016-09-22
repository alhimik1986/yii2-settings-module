<?php

namespace alhimik1986\yii2_settings_module;
use yii\web\AssetBundle;

class DataTablesAsset extends AssetBundle
{
    public $sourcePath ='@vendor/alhimik1986/yii2_settings_module/assets';
    public $css = [
		'js/DataTables/media/css/jquery.dataTables.css',
		'js/DataTables/extras/ColVis/media/css/dataTables.colVis.css',
    ];
    public $js = [
    	'js/DataTables/media/js/jquery.dataTables.min.js',
    	'js/DataTables_plugins/api/fnLengthChange.js',
		
    	'js/DataTables/extras/ColVis/media/js/dataTables.colVis.min.js',
    	'js/fixedTableHeader/jquery-fixed-table-header.js',
    ];
    public $depends = [
		'alhimik1986\yii2_settings_module\SettingsAsset',
		'yii\web\JqueryAsset',
	];
}
