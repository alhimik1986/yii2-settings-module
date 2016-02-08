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
		aLengthMenu: [[10, 30, 100, -1], [10, 30, 100, 'Все']],               // Варианты числа строк на странице
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
			}).attr('title', 'Нажмите "Esc" для очистки поиска')
			.parents('.dataTables_wrapper').find('.ColVis_MasterButton span').html('Показать / скрыть колонки');
		},
		
		oLanguage: {                                                          // Перевод интерфейса
			sLengthMenu: 'Показать: _MENU_ строк',
			sZeroRecords: 'Нет записей.',
			sInfo: 'Строки: _START_ - _END_ из _TOTAL_',
			sInfoEmpty: 'Строки: 0 - 0 из 0',
			sInfoFiltered: '(Найдено из _MAX_ строк)',
			sSearch: 'Поиск:',
			oPaginate: {
				sNext: 'Следующ.',
				sPrevious: 'Предыд.',
				//sFirst: 'Первая',
				sFirst: 'Перв.',
				//sLast: 'Последняя'
				sLast: 'Посл.'
			},
			sLoadingRecords: 'Загрузка...',
			sProcessing: 'Обработка...',
			oAria: {
				sSortAscending: ': включить сортировку по возрастанию',
				sSortDescending: ': включить сортировку по убыванию'
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