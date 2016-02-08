<?php
$this->title = 'Системные настройки';              // Заголовок страницы
$this->params['breadcrumbs'] = array('Системные настройки'); // Хлебные крошки (навигация сайта)
echo $this->render('_index_js');                 // Подключаю java-скрипты
echo $this->render('_index_DataTables');                 // Подключаю java-скрипты
?>

<?php // Таблица с списком настроек ?>
<table id="urv-table" class="urv-table">
	<thead>
		<tr>
			<th style="width:80px"><?php  echo $model->getAttributeLabel('name');        ?></th>
			<th style="width:120px"><?php echo $model->getAttributeLabel('label');       ?></th>
			<th style="width:600px"><?php echo $model->getAttributeLabel('description'); ?></th>
			<th><?php                     echo $model->getAttributeLabel('value');       ?></th>
		</tr>
	</thead>
	<tbody>
		<?php echo $this->render('_rows', array('data'=>$data)); ?>
	</tbody>
</table>