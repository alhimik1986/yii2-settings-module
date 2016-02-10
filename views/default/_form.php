<?php
/*
	Форма создания и редактирования.
	
	@var $model  SettingsModel (CActiveRecord) - Модель настроек
	@var $formTitle                            - Заголовок формы
*/
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use alhimik1986\yii2_settings_module\Module;

// $className = $model::className();
// $name = $className.'[value]';
// $yii_t_label = $model->name;
// $value = $model->value;
// textFields($className, $name, $value, $yii_t_label);

function textFields($className, $name, $value, $yii_t_label) {
	if (is_array($value)) {
		$result = '';
		foreach($value as $key=>$val) {
			$result .= textFields($className, $name .'['.$key.']', $val, $yii_t_label.'.'.$key);
		}
	} else {
		$id = $className.'_value';
		$result = 
			'<label for="'.$id.'">'.
				Module::t('settings', $yii_t_label).':'.
				Html::textInput($name, $value).
			'</label>';
	}
	return $result;
}

?>
<div class="ajax-form" style="width:600px;" id="settings-ajax-form">
	<?php $form=ActiveForm::begin(array(
		'id'=>'urv-form',
		'enableClientValidation' => true,
		'options'=>array('tabindex'=>1),
	)); ?>

		<?php // Шапка формы ?>
		
		<table class="ajax-form-head"><tr>
			<td style="width:24px;"><div class="ajax-form-close" title="<?php echo Module::t('app', 'Close'); ?>"></div></td>
			<td class="ajax-form-title"><?php echo $formTitle; ?></td>
			<td style="width:24px;"><div class="ajax-form-close" title="<?php echo Module::t('app', 'Close'); ?>"></div></td>
		</tr></table>
		
		<?php // Тело формы ?>
		
		<div class="ajax-form-body urv-user-form">
			<div>
				<?php // Если тип json (массив), то вывожу все элементы массива в виде текстовых полей, иначе вывожу одну текстовую область.
				echo textFields($model::className(), $model::className().'[value]', $model->value, $model->name);
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
					<button id="urv-form-button-submit" class="ajax-form-button-submit" type="button" style="min-width: 90px;"><?php echo Module::t('app', 'Save'); ?></button>
					<button id="urv-form-button-cancel" class="ajax-form-button-cancel" type="button" style="margin-left:10px;"><?php echo Module::t('app', 'Cancel'); ?></button>
				</td>
			</tr></table>
		</div>
	<?php ActiveForm::end(); ?>
	<div class="resizable"></div>
</div>