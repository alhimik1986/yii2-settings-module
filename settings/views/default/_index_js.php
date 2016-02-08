<?php use yii\helpers\Url; ?>

<script type="text/javascript">
$(document).ready(function(){
	var isTouchDevice = 'ontouchstart' in document.documentElement; // Имеется ли в системе тачскрин
	<?php // Форма редактирования ?>
	new ajaxForm({
 		form: {
			selector: '#settings-ajax-form'
		},
		create: {
			delegator: '#urv-table',
			selector: 'tr[data_id]',
			on: isTouchDevice ? 'click' : 'dblclick',
			ajax: function(settings) {
				return {
					url:  '<?php echo Url::toRoute('form'); ?>',
					data: {id: settings.create.dom.attr('data_id')}
				};
			},
			success: function(data, settings) {
				$(settings.form.selector).remove();
				<?php // В этой функции обязательно нужно вернуть jQuery-объект полученной формы для ее последующей обработки ?>
				return $(data).appendTo('body');
			},
			afterSuccess: function(settings) {
				var form = settings.form.dom;
				form.find('input[type="text"]:first').focus();
			}
		},
		submit: {
			selector: '.ajax-form-button-submit',
			ajax: function(settings) {
				var form = settings.submit.dom.parents('form');
				return {
					url: form.attr('action'),
					data: form.serializeArray()
				};
			}
		},
		afterSubmit: {
			ajax: function(settings) {
				return {
					url: '<?php echo Url::toRoute('rows'); ?>'
				};
			},
			success: function(data, settings) {
				dataTables['#urv-table']['dom'].fnDestroy();
				$('#urv-table tbody').html(data);
				dataTables['#urv-table']['dom'] = $('#urv-table').dataTable(dataTables['#urv-table']['options']);
				
				$(settings.form.selector).remove(); <?php // Закрываю форму только после удачной записи и обновлении таблицы ?>
				$('#urv-table').css('width', '100%');
			}
		}
	});
});
</script>