$(function() {
	(function($) {
        $.datepicker.regional['cs'] = {
            renderer: $.ui.datepicker.defaultRenderer,
            monthNames: ['leden','únor','březen','duben','květen','červen', 'červenec','srpen','září','říjen','listopad','prosinec'],
            monthNamesShort: ['led','úno','bře','dub','kvě','čer','čvc','srp','zář','říj','lis','pro'],
            dayNames: ['neděle', 'pondělí', 'úterý', 'středa', 'čtvrtek', 'pátek', 'sobota'],
            dayNamesShort: ['ne', 'po', 'út', 'st', 'čt', 'pá', 'so'],
            dayNamesMin: ['ne','po','út','st','čt','pá','so'],
            dateFormat: 'dd.mm.yy',
            firstDay: 1,
            prevText: '&#x3c;Dříve', prevStatus: '',
            prevJumpText: '&#x3c;&#x3c;', prevJumpStatus: '',
            nextText: 'Později&#x3e;', nextStatus: '',
            nextJumpText: '&#x3e;&#x3e;', nextJumpStatus: '',
            currentText: 'Nyní', currentStatus: '',
            todayText: 'Nyní', todayStatus: '',
            clearText: '-', clearStatus: '',
            closeText: 'Zavřít', closeStatus: '',
            yearStatus: '', monthStatus: '',
            weekText: 'Týd', weekStatus: '',
            dayStatus: 'DD d MM',
            defaultStatus: '',
            isRTL: false
        };
        $.extend($.datepicker.defaults, $.datepicker.regional['cs']);
	})(jQuery);	
	
	$.datepicker.setDefaults( $.datepicker.regional[ "cs" ] );	
	
	$(".datepicker").datepicker({
      	changeMonth: true,
      	changeYear: true,
      	showOn: "both",
      	buttonImage: "images/calendar.png",
      	buttonImageOnly: true,
      	yearRange: "-110:+0"
    });
});