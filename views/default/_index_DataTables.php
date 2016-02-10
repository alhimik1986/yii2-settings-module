<?php use alhimik1986\yii2_settings_module\Module; ?>
<script type="text/javascript">
var dataTables = {}; // Список плагинов DataTables с информацией (селектор и опции плагина)

$(document).ready(function(){
var selector   = '#urv-table';
	var options = {
		/*bInfo: false,                                                       // Показывать число записей в таблице
		bPaginate: false,*/                                                   // Пейджер
		sPaginationType: 'full_numbers',                                      // Тип пейджера (расширенный)
		bStateSave: false,                                                    // Сохранять в cookie строку поиска
		sDom: 'C<"top"flip><"clear">rt<"bottom"ip>',                          // Расположение элементов
		oColVis: {                                                            // Панель "Показывать-скрыть колонки" при наведении мыши
			//activate: 'mouseover'
		},
		aLengthMenu: [[10, 30, 100, -1], [10, 30, 100, '<?php echo Module::t('app', 'All'); ?>']], // Варианты числа строк на странице
		iDisplayLength: -1,                                                   // Число строк в странице по умолчанию (Все)
		
        // aoColumnDefs: [{'bSortable': false, 'aTargets':[1]}],              // Игнорировать сортировку
		// aaSorting: [[ 1, 'asc' ]],                                         // Сортировка колонок по умолчанию
		aaSorting: [],                                                        // Не сортировать по умолчанию
		
		fnDrawCallback: function(o){                                          // Автоматически скрываю пейджер, если в нем нечего листать
			if (o._iDisplayLength == -1) {
				$(o.nTableWrapper).find('.dataTables_paginate').hide();
			} else if (o.aoData.length <= o._iDisplayLength) {
				$(o.nTableWrapper).find('.dataTables_paginate').hide();
			} else {
				$(o.nTableWrapper).find('.dataTables_paginate').show();
			}
		},
		fnInitComplete: function(){                                           // Очищаю поиск при нажатии кнопки "Esc"
			var that = this;
			that.parents('.dataTables_wrapper').find('.dataTables_filter input').on('keyup', function(e){
				if (e.which == 27) {
					$(this).val('');
					that.fnFilter('');
				}
			}).attr('title', '<?php echo Module::t('app', 'Press "Esc" to clean the search field'); ?>.')
			.parents('.dataTables_wrapper').find('.ColVis_MasterButton span').html('<?php echo Module::t('app', 'Show / Hide columns'); ?>');
		},
		// Перевод интерфейса
		oLanguage: {
			sLengthMenu: '<?php echo Module::t('app', 'Show: _MENU_ rows'); ?>',
			sZeroRecords: '<?php echo Module::t('app', 'No data.'); ?>',
			sInfo: '<?php echo Module::t('app', 'Rows: _START_ - _END_ of _TOTAL_'); ?>',
			sInfoEmpty: '<?php echo Module::t('app', 'Rows: 0 - 0 of 0'); ?>',
			sInfoFiltered: '<?php echo Module::t('app', '(Found in _MAX_ rows)'); ?>',
			sSearch: '<?php echo Module::t('app', 'Search:'); ?>',
			oPaginate: {
				sNext: '<?php echo Module::t('app', 'Next'); ?>',
				sPrevious: '<?php echo Module::t('app', 'Prev'); ?>',
				//sFirst: '<?php echo Module::t('app', 'First'); ?>',
				sFirst: '<?php echo Module::t('app', 'First.'); ?>',
				//sLast: '<?php echo Module::t('app', 'Last'); ?>'
				sLast: '<?php echo Module::t('app', 'Last.'); ?>'
			},
			sLoadingRecords: '<?php echo Module::t('app', 'Loading...'); ?>',
			sProcessing: '<?php echo Module::t('app', 'Processing...'); ?>',
			oAria: {
				sSortAscending: '<?php echo Module::t('app', ': enable sort by asc'); ?>',
				sSortDescending: '<?php echo Module::t('app', ': enable sort by desc'); ?>'
			}
		}
	};
	$.fn.dataTable.ext.oPagination.iFullNumbersShowPages = 6; // Число страниц в пейджере
	
	dataTables[selector] = {
		selector: selector,
		options: options
	};

	// Запуск DataTables
	if ($(selector).length != 0) {
		dataTables[selector]['dom'] = $(selector).dataTable(options);
		$(selector).fixedTableHeader();  // Прилипание шапки таблицы
		//new FixedHeader( DataTables ); // Прилипание шапки таблицы (Не довели до ума разработчики)
		$('.dataTables_filter input:first').focus();
	}

});
</script>