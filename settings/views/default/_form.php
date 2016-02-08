<?php
/*
	Форма создания и редактирования.
	
	@var $model  SettingsModel (CActiveRecord) - Модель настроек
	@var $formTitle                            - Заголовок формы
*/
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
?>
<div class="ajax-form" style="width:600px;" id="settings-ajax-form">
	<?php $form=ActiveForm::begin(array(
		'id'=>'urv-form',
		'enableClientValidation' => true,
	)); ?>

		<?php // Шапка формы ?>
		
		<table class="ajax-form-head"><tr>
			<td style="width:24px;"><div class="ajax-form-close" title="Закыть"></div></td>
			<td class="ajax-form-title"><?php echo $formTitle; ?></td>
			<td style="width:24px;"><div class="ajax-form-close" title="Закыть"></div></td>
		</tr></table>
		
		<?php // Тело формы ?>
		
		<div class="ajax-form-body urv-user-form">
			<div>
				<?php // Если тип json (массив), то вывожу все элементы массива в виде текстовых полей, иначе вывожу одну текстовую область.
				if (is_array($model->value)) {
					foreach($model->value as $key=>$value) {
						if (is_array($value)) {
							foreach($value as $k=>$v) {
								$name = $model::className().'[value]['.$key.']['.$k.']';
								echo '<label>'."[$key][$k]".':'.Html::textInput($name, $v).'</label>';
							}
						} else {
							$id = $model::className().'_'.$key;
							$name = $model::className().'[value]['.$key.']';
							echo '<label for="'.$id.'">'.$key.':'.Html::textInput($name, $value).'</label>';
						}
					}
				} else {
					echo $form->field($model, 'value');
				}
				?>
				
				<?php echo Html::error($model, 'value'); ?>
			</div>
			
			<br>
			
			<div>
				<?php echo $form->field($model, 'label')->textArea(array(
					'style'=>'width:99%;height:40px;',
					'placeholder'=>'Пусто...',
				)); ?>
				<?php echo Html::error($model, 'label'); ?>
			</div>
			
			<br>
			
			<div>
				<?php echo $form->field($model, 'description')->textArea(array(
					'style'=>'width:99%;',
					'placeholder'=>'Пусто...',
				)); ?>
				<?php echo Html::error($model, 'description'); ?>
			</div>
		</div>
		
		<?php // Подвал формы ?>
		
		<div class="ajax-form-footer">
			<table><tr>
				<td style="text-align:right;">
					<button id="urv-form-button-submit" class="ajax-form-button-submit" type="button">Сохранить</button>
					<button id="urv-form-button-cancel" class="ajax-form-button-cancel" type="button" style="margin-left:10px;">Отмена</button>
				</td>
			</tr></table>
		</div>
	<?php ActiveForm::end(); ?>
	<div class="resizable"></div>
</div>