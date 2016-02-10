/**
 * ajax-форма для Yii.
 * @author Сидорович Николай <sidorovich21101986@mail.ru>
 * @link http://alhimik1986.tw1.ru
 * @copyright Copyright &copy; 2013
 * @license MIT
 * @depends jquery 1.7+ http://jquery.com/
 * @depends jquery.noty http://ned.im/noty/ (необязательно, но без нее не будут отображаться сообщения)
 * @depends jquery.event.drag 2.x http://threedubmedia.com (необязательно, без нее форму будет нельзя перемещать)
 */
var ajaxForm = function(){};          // Главная функция
var ajaxFormMessage = function(){};   // Вывод сообщения (в стиле wordpress) в заданный селектор и сообщение noty
var ajaxFormResizable = function(){}; // Функция, которая позволяет элементу менять размеры

(function($){
	// Главная функция.
	ajaxForm = function(options) {
		
		var settings; // Текущие настройки формы
		
		// Параметры по умолчанию.
		this.defaults = {
			csrf: {},  // Значение csrf для Yii framework, например, scrf: {YII_CSRF_TOKEN: d156a17a08c8bdaa5220da87a493da0abd07dbc6}.
			loadingDom: function(settings){return null;}, // jQuery-объект элемента, на котором показывать значок загрузки данных (если указан параметр dataTable, то loadingDom указывать не нужно)
			
			// Собственно, форма (используется для плагина jquery.event.drag)
			form: {
				selector: '.ajax-form',         // Селектор обертки формы (включающую голову, тело и подвал формы)
				header:   '.ajax-form-title',   // Селектор элемента, при перемещении которого перемещается вся форма
				resizable:'.resizable',         // Селектор элемента для изменения размеров формы
				dom: null                       // jQuery-объект формы, которая отправляет данные
			},
			// Кнопки закрытия формы
			close: {
				selector: '.ajax-form-close, .ajax-form-button-cancel', // Селектор, при клике на который закрывается форма
				on:       'click'             // Событие, при котором закрывается форма
			},
			
			// Инициализация формы, которая уже есть на странице
			initForm: {
				selector  : '.ajax-form'       // Селектор формы, имеющейся на странице на странице
			},
			
			// Кнопки появления формы
			create: {
				selector  : '.ajax-form-create',// Селектор, при клике на который появляется форма
				on        : 'click',           // Событие, при котором появляется форма
				delegator : document,          // Делегатор, который при клике ищет selector: $(delegator).on(on, selector, function(){});
				ajax: function(settings){return false;},// Функция, которая выполняется перед запросом и должна 
					                           // возвращать параметры для $.ajax(params) в виде ассоциативного массива, 
				                               // которые потом смешиваются с данными по умолчанию, которые приведены ниже;
				                               // если возвращаемое значение = false, то отправка производиться не будет.
				url       : '',                // url-адрес страницы, откуда запрашивается форма (в AJAX-запросе)
				type      : 'get',             // Тип запроса (в AJAX-запросе)
				data      : {},                // Отправляемые данные (в AJAX-запросе)
				dataType  : 'json',            // Тип получаемых ответных данных (в AJAX-запросе)
				success   : function(data,settings){}, // Функция, которая выполняется, если запрос успешен (с проверкой наличия data.success)
				                                       // В этой функции обязательно нужно вернуть jQuery-объект полученной формы для ее последующей обработки (для функции afterSuccess)
				afterSuccess: function(settings,dom){},// Функция, которая выполняется после функции success (служит для последующей обработки появившейся формы, например, сделать форму перемещаемой, применить jquery.chosen для выпадающиего списка внутри формы и т.д.)
				notValid  : function(data,settings){}, // Функция, которая выполняется, если отправляемые данные не валидны.
				error     : function(xhr, settings){}, // Функция, которая выполняется, если в результате запроса произошла ошибка
				errorSelector: '#error',       // Место, куда выводить текст ошибки
				
				//top       : 40,                // Расстояние формы от верха окна
				//left      : 150,               // Расстояние от формы до левого края
				top       : 'auto',            // Расстояние формы от верха окна (по умолчанию центруется по вертикали)
				left      : 'auto',            // Расстояние от формы до левого края (по умолчанию центруется по горизонтали)
				dom       : null,              // jQuery-объект элемента, который вызвал событие
				e         : null,              // Параметр события при клике (нужен для внутренних целей, как глобальная переменная)
				xhr       : null,              // Значение, возвращаемое функцией $.ajax()
				timeout   : 50000              // Таймаут ajax-запроса
			},
			// Кнопка отправки данных с формы
			submit: {
				selector  : '.ajax-form-button-success',
				on        : 'click',
				ajax: function(settings){return false;},// Функция, которая выполняется перед запросом и должна 
					                           // возвращать параметры для $.ajax(params) в виде ассоциативного массива, 
				                               // которые потом смешиваются с данными по умолчанию, которые приведены ниже;
				                               // если возвращаемое значение = false, то отправка производиться не будет.
				url       : '',                // url-адрес страницы, откуда запрашивается форма (в AJAX-запросе)
				type      : 'post',            // Тип запроса (в AJAX-запросе)
				data      : {},                // Отправляемые данные (в AJAX-запросе)
				dataType  : 'json',            // Тип получаемых ответных данных (в AJAX-запросе)
				success   : function(data,settings){}, // Функция, которая выполняется, если запрос успешен (с проверкой наличия data.success)
				                                       // В этой функции обязательно нужно вернуть jQuery-объект полученной формы для ее последующей обработки (для функции afterSuccess)
				afterSuccess: function(settings,dom){},// Функция, которая выполняется после функции success (служит для последующей обработки появившейся формы, например, сделать форму перемещаемой, применить jquery.chosen для выпадающиего списка внутри формы и т.д.)
				notValid  : function(data,settings){}, // Функция, которая выполняется, если отправляемые данные не валидны.
				error     : function(xhr){},   // Функция, которая выполняется, если в результате запроса произошла ошибка
				errorSelector: '#error',       // Место, куда выводить текст ошибки
				
				dom: null,                     // jQuery-объект элемента, который вызвал событие
				e         : null,
				xhr       : null,              // Значение, возвращаемое функцией $.ajax()
				timeout   : 50000              // Таймаут ajax-запроса
			},
			// Действия после удачной отправки данных с формы (выполняется, если submit.success что-нибудь возвращает; хотя бы пустой массив)
			// Нужна, например, если после отправки формы нужно обновить поле таблицы ajax-запросом.
			afterSubmit: {
				init      : function(){},
				ajax: function(settings){return false;},// Функция, которая выполняется перед запросом и должна 
					                           // возвращать параметры для $.ajax(params) в виде ассоциативного массива, 
				                               // которые потом смешиваются с данными по умолчанию, которые приведены ниже;
				                               // если возвращаемое значение = false, то отправка производиться не будет.
				url       : '',                // url-адрес страницы, откуда запрашивается форма (в AJAX-запросе)
				type      : 'get',             // Тип запроса (в AJAX-запросе)
				data      : {},                // Отправляемые данные (в AJAX-запросе)
				dataType  : 'json',            // Тип получаемых ответных данных (в AJAX-запросе)
				success   : function(data,settings){}, // Функция, которая выполняется, если запрос успешен (с проверкой наличия data.success)
				                                       // В этой функции обязательно нужно вернуть jQuery-объект полученной формы для ее последующей обработки (для функции afterSuccess)
				afterSuccess: function(settings,dom){},// Функция, которая выполняется после функции success (служит для последующей обработки появившейся формы, например, сделать форму перемещаемой, применить jquery.chosen для выпадающиего списка внутри формы и т.д.)
				notValid  : function(data,settings){}, // Функция, которая выполняется, если отправляемые данные не валидны.
				error     : function(xhr){},   // Функция, которая выполняется, если в результате запроса произошла ошибка
				errorSelector: '#error',       // Место, куда выводить текст ошибки
				e         : null,
				xhr       : null,              // Значение, возвращаемое функцией $.ajax()
				timeout   : 50000              // Таймаут ajax-запроса
			},
			// Проверяет наличие переменной
			isset: function(data) { return (typeof(data) !== 'undefined' && data !== null); },
			_currentSetting: '', // Текущая настройка (вспомогательный элемент для функции _error())
			_loadingRequests: [], // Счетчик запросов (для внутренних нужд функции showLoading()
			
			// Специфическая вспомогательная функция для плагина dataTables.js. Нужна для заполнения таблицы данными при загрузке страницы.
			// Если указать парметр ajax, то данные будут запрашиваться ajax-запросом и перезапишут параметр dataTableData (данные для заполнения таблицы).
			dataTableOptions: {},             // настройки плагина dataTables
			updateTooltip: function(){},      // Функция, которая обновляет данные во всплывающих подсказках
			dataTable: {
				init: function(settings) {},
				selector: '',                 // css-селектор таблицы, к которой применить плагин dataTables
				dom: null,                    // jQuery-объект DataTables
				dataTableData: {},            // данные для заполнения таблицы
				dataTable: function(settings){}, // внутренняя функция, которая применяет плагин dataTables
				
				// для формирования ajax-запроса данных для заполнения таблицы
				ajax: function(settings){return false;},// Функция, которая выполняется перед запросом и должна 
					                           // возвращать параметры для $.ajax(params) в виде ассоциативного массива, 
				                               // которые потом смешиваются с данными по умолчанию, которые приведены ниже;
				                               // если возвращаемое значение = false, то отправка производиться не будет.
				url       : '',                // url-адрес страницы, откуда запрашивается форма (в AJAX-запросе)
				type      : 'get',             // Тип запроса (в AJAX-запросе)
				data      : {},                // Отправляемые данные (в AJAX-запросе)
				dataType  : 'json',            // Тип получаемых ответных данных (в AJAX-запросе)
				success   : function(data,settings){}, // Функция, которая выполняется, если запрос успешен (с проверкой наличия data.success)
				                                       // В этой функции обязательно нужно вернуть jQuery-объект полученной формы для ее последующей обработки (для функции afterSuccess)
				afterSuccess: function(settings,dom){},// Функция, которая выполняется после функции success (служит для последующей обработки появившейся формы, например, сделать форму перемещаемой, применить jquery.chosen для выпадающиего списка внутри формы и т.д.)
				notValid  : function(data,settings){}, // Функция, которая выполняется, если отправляемые данные не валидны.
				error     : function(xhr){},   // Функция, которая выполняется, если в результате запроса произошла ошибка
				errorSelector: '#error',       // Место, куда выводить текст ошибки
				xhr       : null,              // Значение, возвращаемое функцией $.ajax()
				timeout   : 50000,             // Таймаут ajax-запроса
				
				// Вспомогательные функции, которые будут использоваться внутри ajaxForm.create, ajaxForm.submit и т.д.
				updateRow: function(data, $tr, settings){},
				updateAll: function(data, settings){}
			}
		};
		
		
		
		// *********************************************************************************************************
		// **************** Вунтренние вспомогательные функции *****************************************************
		// *********************************************************************************************************
		
		// Проверяет наличие сообщений в полученных данных и, если они есть, то выводит их
		var _messages = function(data, setting) {
			var messages, message, type;
			if ( settings.isset(data) && settings.isset(data.messages) ) {
				messages = data.messages;
				for (key in messages) {
					for (type in messages[key]) {
						settings.message(setting.errorSelector, type, messages[key][type]);
					}
				}
			}
		};
		
		// Функция, которая выполняется, если в результате запроса произошла ошибка
		var _error = function(xhr, exception) {
			// Очищаю предыдущие сообщения об ошибках
			_clearPreviousErrorMessages($(settings.form.selector));
			
			if (exception === 'timeout') {
				settings.message(settings._currentSetting.errorSelector, 'error', 'Сервер не отвечает или находится не в сети. Если ошибка будет продолжаться, то обратитесь к администратору сайта.');
			} else if (xhr.status === 12007) {
				settings.message(settings._currentSetting.errorSelector, 'error', 'Проверьте подключение к локальной сети.');
			} else {
				settings.message(settings._currentSetting.errorSelector, 'error', xhr.responseText);
			}
			settings._currentSetting.error(xhr, settings);
			
			settings.showLoading(false);
		};
		
		// Функция, очищает предыдущие сообщения об ошибках
		var _clearPreviousErrorMessages = function(form) {
			var tag;
			tag = form.find('.error').not(':has(input,select,textarea)');  if (tag.length != 0) tag.remove();
			tag = form.find('.errorMessage');                              if (tag.length != 0) tag.hide();
			tag = form.find('.label-error');                               if (tag.length != 0) tag.removeClass('label-error');
			tag = form.find('.input-error');                               if (tag.length != 0) tag.removeClass('input-error');
			tag = form.find('.input-error');                               if (tag.length != 0) tag.next('.chzn-container').removeClass('input-error');
		};
		
		// Если данные не валидны
		var _notValid = function(data, setting) {
			var model, attribute, name, form = $(settings.form.selector);
			
			// Очищаю предыдущие сообщения об ошибках
			_clearPreviousErrorMessages(form);
			
			for (model in data) {
				for (attribute in data[model]) {
					for (key in data[model][attribute]) {
						name = model+'_'+attribute;
						if (key == 0) { // Вывожу под текстовым полем только первую ошибку
							form.find('#'+name+'_em_').show().html('<div class="error">'+data[model][attribute][key]+'</div>').show();;
							form.find('label[for="'+name+'"]').addClass('label-error');
							form.find('#'+name).addClass('input-error');
							form.find('#'+name).next('.chzn-container').addClass('input-error');
						}
						// Сообщения выводятся все подряд.
						// Если некуда вывести ошибку валидации, то вывожу ее обычным сообщением
						if ( form.find('#'+name+'_em_').length == 0 ) settings.message(setting.errorSelector, 'error', data[model][attribute][key]);
						break;
					}
				}
			}
			
			settings.showLoading(false);
		};
		
		// Если запрос успешен
		var _success = function(data) {
			var setting = settings._currentSetting;
			
			if ( settings.isset(data) &&  settings.isset(data.status) && data.status == 'success' ) {
				settings.form.dom = setting.success(data.content, settings);
				setting._afterSuccess(setting);
				setTimeout(function(){
					setting.afterSuccess(settings, settings.form.dom);
				}, 1);
			} else if ( settings.isset(data) && settings.isset(data.status) && data.status == 'error' ) {
				setting._notValid(data.content, setting);
				setting.notValid(data.content, settings);
			} else {
				settings.message(setting.errorSelector, 'error', data);
			}
			settings._messages(data, setting); // Проверяю наличие сообщений и вывожу их при наличии
			
			settings.showLoading(false);
		};
		
		// Если запрос успешен (для submit)
		var _success_submit = function(data) {
			var setting = settings.submit;
			
			if ( settings.isset(data) && settings.isset(data.status) && data.status == 'success') {
				setting.success(data.content, settings);
				setting._afterSuccess(setting);
				setting.afterSuccess(settings);
				// Выполняю "Действия после удачной отправки формы" (afterSubmit), если в settings указан хоть один параметр этой функции
				if ( settings.isset(settings.options.afterSubmit)) {
					settings.afterSubmit._send(settings.afterSubmit);
				}
			} else if (settings.isset(data) && settings.isset(data.status) && data.status == 'error') {
				setting._notValid(data.content, setting);
				setting.notValid(data.content, settings);
			} else {
				settings.message(setting.errorSelector, 'error', data);
			}
			settings._messages(data, setting); // Проверяю наличие сообщений и вывожу их при наличии
			
			settings.showLoading(false);
		};
		
		// Появление формы, если запрос успешен. 
		var success = function(data, settings) {
			$(settings.form.selector).remove();
			settings.showLoading(false);
			// В этой функции обязательно нужно вернуть jQuery-объект полученной формы для ее последующей обработки
			return $(data).appendTo('body');
		};
		
		// Системная пост-обработка формы (делаю форму перемещаемой)
		var _afterSuccess = function(setting) {
			//var form = settings.form.dom;
			//var e = setting.e, posX = e.pageX, posY = e.pageY, minTop = setting.minTop, maxLeft = setting.maxLeft, posY = minTop, posX = maxLeft;
			//if ((e.pageY - $(window).scrollTop()) > minTop) posY = $(window).scrollTop() + minTop;
			//if ((e.pageX - $(window).scrollLeft()) > maxLeft) posX = $(window).scrollLeft() + maxLeft;
			var form = settings.form.dom;
			var e = setting.e,
				posX = (setting.left == 'auto') ? $(window).scrollLeft() + Math.round(($(window).width()  - form.width())  / 2) : setting.left,
				posY = (setting.top == 'auto')  ? $(window).scrollTop()  + Math.round(($(window).height() - form.height()) / 5) : setting.top;
			
			// Задаю форме начальное положение
			form.css({
				position: 'absolute',
				top: posY,
				left: posX
			});
			// Если jquery.event.drag загружен, то делаю форму перемещаемой
			var processing = false;
			if ($.fn.drag) {
				form.drag(function(ev, dd){
					var $this = $(this);
					if (processing) return false;
					setTimeout(function(){
						processing = true;
						$this.css({
							top: dd.offsetY,
							left: dd.offsetX
						});
						processing = false;
					}, 10);
				}, { handle: settings.form.header });
			}
			
			// Даю форме изменять размеры
			ajaxFormResizable(form, $(settings.form.resizable));
			
			// Привязываю событие для кнопки "Отравка данных с формы"
			//form.find(settings.submit.selector).on(settings.submit.on, function(e) {
			form.on(settings.submit.on, settings.submit.selector, function(e) {
				settings.submit.dom = $(this);
				settings.submit.e = e;
				settings.submit._send(settings.submit);
				return false;
			});
			
			// Привязываю событие для кнопки "Закрытие формы"
			form.find(settings.close.selector).on(settings.close.on, function(e) {
				$(this).parents(settings.form.selector).remove();
			});
		};
		
		// Отправка запроса
		var _send = function(setting) {
			var ajaxOptions = setting.ajax(settings);
			if (ajaxOptions === false) return;
			if ( ! settings.isset(ajaxOptions) || ! settings.isset(ajaxOptions['ajax'])) setting.data = {};
			$.extend(true, setting, ajaxOptions);
			if (setting.type == 'post') $.extend(true, setting.data, settings.csrf);
			
			settings._currentSetting = setting;
			
			settings.showLoading(true);
			
			setting.xhr = $.ajax({
				type:     setting.type,
				url:      setting.url,
				dataType: setting.dataType,
				data:     setting.data,
				success:  setting._success,
				error:    setting._error,
				timeout:  setting.timeout,
				cache:    false  // т.к. IE кэширует ajax-запросы
			});
		};
		
		
		
		// **************************************************************************************************************
		// Функция: ajaxFormMessage() - вывод сообщения (в стиле wordpress) в заданный селектор и сообщение noty
		// **************************************************************************************************************
		
		this.defaults.message = ajaxFormMessage;
		// Проверяет наличие сообщений в полученных данных и, если они есть, то выводит их
		this.defaults._messages = _messages;
		
		
		
		// *********************************************************************************************************
		// **************** Инициализация уже загруженной формы ****************************************************
		// *********************************************************************************************************
		
		this.defaults.initForm._init = function() {
			if ( ! settings.isset(settings.options.initForm) ) return;
			
			settings.form.selector = settings.initForm.selector;
			settings.form.dom = $(settings.initForm.selector).is('form') ? $(settings.initForm.selector) : $(settings.initForm.selector).find('form');
			var form = settings.form.dom;
			
			// Привязываю событие для кнопки "Отравка данных с формы"
			//$(settings.form.selector).find(settings.submit.selector).on(settings.submit.on, function(e) {
			$(settings.form.selector).on(settings.submit.on, settings.submit.selector, function(e) {
				settings.submit.dom = $(this);
				settings.submit.e = e;
				settings.submit._send(settings.submit);
				return false;
			});
		};
		
		
		
		// *********************************************************************************************************
		// **************** Появление формы ************************************************************************
		// *********************************************************************************************************
		
		this.defaults.create._error        = _error;
		this.defaults.create._notValid     = _notValid;
		this.defaults.create._success      = _success;
		this.defaults.create.success       = success;
		this.defaults.create._afterSuccess = _afterSuccess;
		this.defaults.create._send         = _send;
		
		// Появление формы
		this.defaults.create._init = function(settings) {
			$(settings.create.delegator).on(settings.create.on, settings.create.selector, function(e) {
				settings.create.dom = $(this);
				settings.create.e = e;
				settings.create._send(settings.create);
				return false;
			});
		};
		
		
		
		// *********************************************************************************************************
		// **************** Отправка данных с формы *************************************************************************
		// *********************************************************************************************************
		
		this.defaults.submit._error        = _error;
		this.defaults.submit._notValid     = _notValid;
		this.defaults.submit._success      = _success_submit;
		this.defaults.submit._afterSuccess = function(){};
		this.defaults.submit._send         = _send;
		
		
		
		// *********************************************************************************************************
		// **************** Действия после удачной отправки данных с формы *****************************************
		// *********************************************************************************************************
		
		this.defaults.afterSubmit._error        = _error;
		this.defaults.afterSubmit._notValid     = _notValid;
		this.defaults.afterSubmit._success      = _success;
		this.defaults.afterSubmit._afterSuccess = function(){};
		this.defaults.afterSubmit._send         = _send;
		
		
		
		// *********************************************************************************************************
		// **************** Специфическая вспомогательная функция для плагина dataTables.js ************************
		// *********************************************************************************************************
		
		this.defaults.dataTable._error        = _error;
		this.defaults.dataTable._notValid     = function(){};
		this.defaults.dataTable._success      = _success;
		this.defaults.dataTable._afterSuccess = function(){};
		this.defaults.dataTable._send         = _send;
		
		this.defaults.dataTable.success = function(data, settings) {
			try {
				settings.dataTable.dataTableData = $.parseJSON(data);
			} catch (e) {
				settings.dataTable.dataTableData = {};
				notyMessage('error', 'Не удалось обработать принятые данные.');
			}
			settings.dataTable.updateAll(settings.dataTable.dataTableData, settings); // Применяю плагин dataTables
		};
		
		// Действия при загрузке страницы (заполняю таблицу данными)
		this.defaults.dataTable._init = function(settings) {
			if ( ! settings.isset(settings.options.dataTable)) return;
			
			settings.dataTable.dataTable(settings);       // Применяю плагин DataTables
			settings.dataTable._send(settings.dataTable); // Обновляю всю таблицу (заполняю данными)
			settings.dataTable.init(settings);
		};
		
		var fnRowCallback = function( nRow, aData, iDisplayIndex, iDisplayIndexFull) {
			if ( ! settings.isset(aData.attributes)) return nRow;
			
			var $nRow = $(nRow); var oldClass1 = '', oldClass2 = ''; var key, k;
			
			for (key in aData.attributes) {
				// Удаляю аттрибут class в строке таблицы, кроме классов "odd" и "even"
				if ($.trim(key) == 'class') {
					oldClass1 = $nRow.hasClass('odd') ? 'odd': '';
					oldClass2 = $nRow.hasClass('even') ? 'even': '';
					$nRow.removeAttr('class');
					if (oldClass1.length != 0) $nRow.addClass(oldClass1);
					if (oldClass2.length != 0) $nRow.addClass(oldClass2);
				}
				
				// аттрибуты для td (если есть)
				if ( Object.prototype.toString.call( aData.attributes[key] ) === '[object Object]') {
					for (k in aData.attributes[key]) {
						if ($.trim(k) == 'class') {
							$nRow.find('td:eq('+key+')').addClass(aData.attributes[key][k]);
						} else {
							$nRow.find('td:eq('+key+')').attr(k, aData.attributes[key][k]);
						}
					}
				} else {
					// аттрибуты для tr
					if ($.trim(key) == 'class') {
						$nRow.addClass(aData.attributes[key]);
					} else {
						$nRow.attr(key, aData.attributes[key]);
					}
				}
			}
			return nRow;
		};
		
		// Функция, которая применяет плагин dataTables
		this.defaults.dataTable.dataTable = function(settings) {
			var dataTableOptions = settings.dataTable.dataTableOptions;
			dataTableOptions['aaData'] = settings.dataTable.dataTableData;
			dataTableOptions['fnRowCallback'] = fnRowCallback;
			
			// Уничтожаю предыдущий экземпляр таблицы, если он есть
			if (settings.dataTable.dom != null && typeof(settings.dataTable.dom.fnDestroy) != 'undefined') settings.dataTable.dom.fnDestroy();
			
			var $dataTables = $(settings.dataTable.selector).dataTable(dataTableOptions);
			if (typeof(settings.dataTable.updateTooltip) === 'function') settings.dataTable.updateTooltip($dataTables);
			$(settings.dataTable.selector).fixedTableHeader();
			$('.dataTables_filter input:first').focus();
			
			// Очищаю переменную для бережения памяти
			settings.dataTable.dataTableData = '';
			
			// Сохраняю jQuery-объект плагина
			settings.dataTable.dom = $dataTables;
			
			// Инициализирую всплывающие подсказки при каждом обновлении таблицы.
			$dataTables.on('draw', function(){
				if (typeof(settings.dataTable.updateTooltip) === 'function') settings.dataTable.updateTooltip($dataTables);
			});
		};
		
		/**
		 * Обновить строку таблицы.
		 * @param object aData Данные таблицы в том же формате, что и для DataTables, НО аттрибуты строки таблицы содержатся в ключе "attributes".
		 * @param jQuery $nRow jQuery-объект строки таблицы ($tr).
		 * @param object settings Настройки текущего объекта ajax-формы.
		 */
		this.defaults.dataTable.updateRow = function(aData, $nRow, settings) {
			//console.log(aData);
			var key, td_key, attr_key, k;
			aData = aData[0];
			
			if (settings.isset(aData.attributes)) {
				for (key in aData.attributes) {
					// Удаляю аттрибут class в строке таблицы, кроме классов "odd" и "even"
					if ($.trim(key) == 'class') {
						oldClass1 = $nRow.hasClass('odd') ? 'odd': '';
						oldClass2 = $nRow.hasClass('even') ? 'even': '';
						$nRow.removeAttr('class');
						if (oldClass1.length != 0) $nRow.addClass(oldClass1);
						if (oldClass2.length != 0) $nRow.addClass(oldClass2);
					}
					// аттрибуты для tr
					if ($.trim(key) == 'class') {
						$nRow.addClass(aData.attributes[key]);
					} else {
						$nRow.attr(key, aData.attributes[key]);
					}
				}
			}
			// для каждого td
			if (settings.isset(aData[0]['content'])) {
				var result = [];
				for (td_key in aData) {
					if (td_key == 'attributes')
						continue;
					
					if (settings.isset(aData[td_key]['attributes'])) {
						for(attr_key in aData[td_key]['attributes']) {
							$nRow.find('td:eq('+td_key+')').attr(attr_key, aData[td_key].attributes[attr_key]);
						}
					}
					
					result[td_key] = aData[td_key]['content'];
				}
				aData = result;
			}
			//var attributes = aData['attributes'];
			
			/*settings.dataTable.dom.fnUpdate(aData, $nRow[0]);
			settings.dataTable.dom.fnDraw();*/
			
			// Обновляю строку так, чтобы не сбрасывался пейджер (не уходил на первую страницу)
			settings.dataTable.dom.fnUpdate(aData, $nRow[0], iColumn=undefined, bRedraw=false);
		};
		
		/**
		 * Обновить всю таблицу.
		 * @param object oData Данные таблицы в том же формате, что и для DataTables, НО аттрибуты строки таблицы содержатся в каждом oData[0]['attributes'], oData[1]['attributes'] и т.д.
		 * @param object settings Настройки текущего объекта ajax-формы, в которой должны содержаться настройки (settings.dataTable.dataTableOptions) плагина DataTables.
		 */
		this.defaults.dataTable.updateAll = function(oData, settings) {
			var dataTableOptions = settings.dataTable.dataTableOptions;
			settings.dataTable.dom.fnClearTable();
			
			dataTableOptions['aaData'] = oData;
			dataTableOptions['fnRowCallback'] = fnRowCallback;
			
			settings.dataTable.dom.fnAddData(oData);
			
			// Очищаю поле для бережения памяти
			settings.dataTable.dataTableData = '';
		};
		
		/**
		 * Показать скрыть значок загрузки.
		 * @param boolean show Показать-скрыть значок загрузки.
		 * @param mixed dom jQuery-объект или селектор в виде строки, рядом с которым разместить значок загрузки.
		 */
		this.defaults.showLoading = function(show, dom) {
			if ( ! settings.isset(show)) show = true;
			if ( ! settings.isset(dom)) dom = settings.dataTable.dom;
			if ( ! settings.isset(dom)) dom = settings.loadingDom(settings);
			if (typeof dom == 'string' || dom instanceof String) dom = $(dom);
			if (settings.isset(dom) && dom.length > 0) {
				if (show) {
					if (dom.parent().find('.ajax-loading').length == 0) {
						dom.before('<div class="ajax-loading" style="position:absolute;margin-top:2px;margin-left:3px;"></div>');
					}
					settings._loadingRequests.push(true); // Увеличиваю счетчик отправленных запросов на 1.
				} else {
					settings._loadingRequests.shift(); // Уменьшаю счетчик отправленных запросов на 1.
					// Если число запросов равно числу ответов, то убираю значок загрузки
					if (settings._loadingRequests.length == 0) {
						dom.parent().find('.ajax-loading').remove();
					}
				}
			}
		};
		
		
		
		// *********************************************************************************************************
		// **************** Уничтожение всех обработчиков событий **************************************************
		// **************** Пример: var f = new ajaxForm(); f.destroy(); ******************************************
		// *********************************************************************************************************
		this.defaults.destroy = function() {
			$(document).off(settings.create.on, settings.create.selector);
			$(document).off(settings.close.on, settings.form.selector + ' ' + settings.close.selector);
			$(document).off(settings.submit.on, settings.form.selector + ' ' + settings.submit.selector);
			settings.form.dom && settings.form.dom.off(settings.submit.on, settings.submit.selector);
			ajaxFormResizable(settings.form.dom, $(settings.form.resizable), true);
		};
		
		
		
		// *********************************************************************************************************
		// **************** Запуск *********************************************************************************
		// *********************************************************************************************************
		
		if ( ! $.isPlainObject(options)) {
			options = {};
		}
		
		// Смешиваю полученные настройки (options) с настройками по умолчанию (defaults).
		$.extend(true, this.defaults, options);
		settings = this.defaults; // Получаю текущие настройки формы
		$.extend(true, this, this.defaults);
		
		settings.options = options;
		
		this.initForm._init(settings);  // Запуск "Инициализация уже загруженной формы"
		this.create._init(settings);    // Запуск "Появление формы"
		this.dataTable._init(settings); // Запуск "dataTable"
	};
})(jQuery)


// **************************************************************************************************************
//Функция: notyMessage - выводит всплывающие сообщения с заданным типом и текстом. jquery.noty
// **************************************************************************************************************
var notyMessage = function(type, text) {
	if (typeof(noty) !== 'function') return;
	var n = noty({
			text: text,
			type: type,
			timeout:3000,
		dismissQueue: false,
			layout: 'top',
			theme: 'defaultTheme'
	});
	return n;
};


// **************************************************************************************************************
// Функция: ajaxFormMessage() - вывод сообщения (в стиле wordpress) в заданный селектор и сообщение noty
// @param string selector - css-селектор, куда выводить сообщение
// @param string type     - тип сообщения (success, error, info, warning)
// @param string text     - текст сообщения
// **************************************************************************************************************
ajaxFormMessage = function(selector, type, text) {
	var data = '\
<table class="ajax-form-message-'+type+'">\
	<tr>\
		<td colspan="3" class="ajax-form-message-text">\
			<div class="ajax-form-message-icon-'+type+'"></div>\
			<div class="ajax-form-message-close" onclick="jQuery(this).parents(\'.ajax-form-message-'+type+'\').animate({opacity:0}, 500, function(){jQuery(this).remove();});"></div>\
			<div class="ajax-form-message-text-body">' + text + '</div>\
		</td>\
	</tr>\
</table>';
	jQuery(selector).html(data);
	notyMessage(type, text);
};


// **************************************************************************************************************
// Функция: ajaxFormResizable - позволяет элементу менять размеры
// @param jQuery  dom     - jquery-объект элемента, у которого нужно менять размер
// @param jQuery  handle  - jquery-объект элемента, из-за которого меняется размер
// @param boolean destroy - если true - то удаляю события, связанные с изменением размеров
// # http://stackoverflow.com/questions/4673348/emulating-frame-resize-behavior-with-divs-using-jquery-without-using-jquery-ui
// **************************************************************************************************************
ajaxFormResizable = function(dom, handle, destroy) {
	if (typeof(destroy) != 'undefined' && destroy == true) {
		handle.off('mousedown', mousedown);
		jQuery(document).off('mouseup', mouseup);
		return;
	}
	var p = {}, processing = false, lastE = false;
	
	
	var mousemove = function(e) {
		if ( ! processing) {
			processing = true;
			setTimeout(function(){
				mousemoveHandle();
			}, /MSIE (\d+\.\d+);/.test(navigator.userAgent) ? 30 : 1);
		}
		lastE = e;
	};
	
	var mousemoveHandle = function(){
		dom.css({
			width:  Math.max(p.minWidth,  dom.scrollLeft() - dom.offset().left + lastE.pageX) + 'px',
			height: Math.max(p.minHeight, dom.scrollTop()  - dom.offset().top + lastE.pageY) + 'px'
		});
		processing = false;
	};
	
	var mousedown = function(e) {
		jQuery(document).on('mousemove', mousemove);
		if (typeof(e.originalEvent.preventDefault) == "function") e.originalEvent.preventDefault();
	};
	var mouseup = function() {
		jQuery(document).off('mousemove', mousemove);
	};
	setTimeout(function(){
		p = {minWidth: dom.width(), minHeight: dom.height()};
	}, 1000);
	handle.on('mousedown', mousedown);
	jQuery(document).on('mouseup', mouseup);
	
	// Запрещаю выделение при изменении размера
	handle
		.attr('unselectable', 'on')
		.css('user-select', 'none')
		.on('selectstart', false);
	
};