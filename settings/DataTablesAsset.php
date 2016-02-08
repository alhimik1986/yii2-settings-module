<?php

namespace app\modules\settings;
use yii\web\AssetBundle;

class DataTablesAsset extends AssetBundle
{
	public function init()
	{
		parent::init();
	}
    public $sourcePath = '@app/modules/settings/assets';
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
    public $depends = [];
	public $jsOptions = [ 'position' => \yii\web\View::POS_HEAD ];
}
