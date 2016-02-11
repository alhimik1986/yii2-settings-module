<?php
use alhimik1986\yii2_settings_module\Module;
$pageTitle = Module::t('app', 'System settings');

$this->title = $pageTitle;                         // Заголовок страницы
$this->params['breadcrumbs'] = array($pageTitle);  // Хлебные крошки (навигация сайта)
echo $this->render('_index_js');                   // Подключаю java-скрипты
echo $this->render('_index_DataTables');           // Подключаю java-скрипты

$this->registerCss('#urv-form label {margin-top:5px; display:block;}');
?>

<?php // Таблица с списком настроек ?>
<table id="urv-table" class="urv-table">
	<thead>
		<tr>
			<th style="min-width:110px"><?php echo $model->getAttributeLabel('name');        ?></th>
			<th style="min-width:120px"><?php echo $model->getAttributeLabel('label');       ?></th>
			<th style="min-width:500px"><?php echo $model->getAttributeLabel('description'); ?></th>
			<th><?php                         echo $model->getAttributeLabel('value');       ?></th>
		</tr>
	</thead>
	<tbody>
		<?php echo $this->render('_rows', array('data'=>$data)); ?>
	</tbody>
</table>