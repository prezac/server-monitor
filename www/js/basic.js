   ( function($) {
	if(!$.browser){
	    $.browser={chrome:false,mozilla:false,opera:false,msie:false,safari:false};
	    var ua=navigator.userAgent;
    	    $.each($.browser,function(c,a){
	        $.browser[c]=((new RegExp(c,'i').test(ua)))?true:false;
    	        if($.browser.mozilla && c =='mozilla'){$.browser.mozilla=((new RegExp('firefox','i').test(ua)))?true:false;};
                if($.browser.chrome && c =='safari'){$.browser.safari=false;};
            });
	};

        $.datepicker.regional['cz'] = {
                closeText: 'Zavřít',
                prevText: '<Předchozí',
                nextText: 'Následující>',
                currentText: 'Dnes',
                monthNames: ['Leden','Únor','Březen','Duben','Květen','Červen',
                'Červenec','Srpen','Září','Říjen','Listopad','Prosinec'],
                monthNamesShort: ['Led','Úno','Bře','Dub','Kvě','Črv',
                'Črn','Srp','Zář','Řij','Lis','Pro'],
                dayNames: ['neděle','pondělí','úterý','středa','čtvrtek','pátek','sobota'],
                dayNamesShort: ['Ned','Pon','Úte','Stř','Čtv','Pát','Sob'],
                dayNamesMin: ['Ne','Po','Út','St','Čt','Pá','So'],
                weekHeader: 'V',
                dateFormat: 'dd.mm.yy',
                firstDay: 1,
                isRTL: false,
                showMonthAfterYear: false,
                yearSuffix: ''
        };
        $.timepicker.regional['cz'] = {
                timeOnlyTitle: 'Vyberte čas',
                timeText: 'Čas',
                hourText: 'Hodiny',
                minuteText: 'Minuty',
                secondText: 'Sekundy',
                millisecText: 'Milisekundy',
                timezoneText: 'Časová zóna',
                currentText: 'Nyní',
                closeText: 'Zavřít',
                timeFormat: 'HH:mm',
                amNames: ['AM', 'A'],
                pmNames: ['PM', 'P'],
                isRTL: false
        };
        $.datepicker.setDefaults($.datepicker.regional['cz']);

        $.fn.extend({
        	center: function () {
                	return this.each(function() {
                        	var top = ($(window).height() - $(this).outerHeight()) / 2;
                                var left = ($(window).width() - $(this).outerWidth()) / 2;
                                $(this).css({position:'absolute', margin:0, top: (top > 0 ? top : 0)+'px', left: (left > 0 ? left : 0)+'px'});
                        });
                }
         });

	 $('.number').on('keydown keypress keyup paste input', function () {
	    while ( ($(this).val().split(".").length - 1) > 1 ) {
	        $(this).val($(this).val().slice(0, -1));
	        if ( ($(this).val().split(".").length - 1) > 1 ) {
	            continue;
        	} else {
	            return false;
        	}

	    }
	    $(this).val($(this).val().replace(/[^0-9.]/g, ''));
	    var int_num_allow = 3;
	    var float_num_allow = 1;
	    var iof = $(this).val().indexOf(".");
	    if ( iof != -1 ) {
	        if ( $(this).val().substring(0, iof).length > int_num_allow ) {
        	    $(this).val('');
        	    $(this).attr('placeholder', 'invalid number');
	        }
	        $(this).val($(this).val().substring(0, iof + float_num_allow + 1));
	    } else {
	        $(this).val($(this).val().substring(0, int_num_allow));
	    }
	    return true;
	});

    } ) ( jQuery );

