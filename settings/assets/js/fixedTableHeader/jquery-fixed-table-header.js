$.fn.fixedTableHeader = function () {
	if ( $(this).length == 0) return;
	var t = $(this),
		header = t.find('thead'),
		fixed_table = $('<table></table>').css({ 'display':'none', 'position':'fixed', 'top':'0px', 'background-color':'white' }),
		spacing = (!/(webkit|MSIE)/i.test(navigator.userAgent))
			? parseInt(t.css('border-left-width'))
			: 0,
		spacing2 = (/Firefox/i.test(navigator.userAgent)) ? 1 : 0,
		build = function(){
			fixed_table.find('thead').remove();
			t.parent().append(fixed_table.append(t.find("thead").clone(true,true)));
		},
		resize = function(){
			fixed_table.find("th").each(function (i) {
				var th = t.find("th").eq(i);
				$(this).width(th.width() - spacing + spacing2);
			});
			var width = t.width() + spacing2 + 'px';
			fixed_table.attr('class', t.attr('class')).css('width', width);
		},
		last_left = 0,
		_processing = false,
		processing = false;

    $(window).bind("scroll resize", function (e) {
		// Показываем-скрываем нашу прилипающую шапку
        var offset = $(this).scrollTop();
        if ((offset >= t.offset().top) && fixed_table.is(":hidden") && (offset <=(t.offset().top+t.height()))) fixed_table.show();
        else if (offset < t.offset().top || (offset >=(t.offset().top+t.height()))) fixed_table.hide();
		if (!processing) {
			processing = setTimeout(function(){
				var left = header.offset().left - $(window).scrollLeft() - spacing;
				if (left != last_left || e.type == 'resize') {
					fixed_table.css({
						left : left + 'px'
					});
					build();
					resize();
				}
				processing = false;
				last_left = left;
			}, 300);
		}
		// Перестроение таблицы слишком долгий процесс
		if (!_processing) {
			_processing = setTimeout(function(){
				build();
				resize();
				_processing = false;
			}, 3000);
		}
    });
	
	build();
	resize();
	
	// Обновляю шапку таблицы при клике на нее
	fixed_table.on('click', 'th:visible', function(){
		fixed_table.find('thead').remove();
		build();
		resize();
	});
    return t;
};