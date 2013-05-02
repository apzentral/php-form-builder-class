/*===== jQuery Validation to check for the input =====*/
$(function(){
	var VAL_HELPER = {
		checkMin : function(obj) {
			if (obj.val() != '' && obj.attr('min') > obj.val()) {
				obj.val(obj.attr('min'));
			}
		},
		checkMax : function(obj) {
			if (obj.attr('max') < obj.val()) {
				obj.val(obj.attr('max'));
			}
		},
		checkPattern : function(obj) {
			var regex = new RegExp(obj.attr('pattern'), 'g');
			if ( ! regex.test(obj.val())) {
				obj.addClass('field-error');
			} else {
				obj.removeClass('field-error');
			}
		}
	};

	// Check for Max and Min Attr
	$('input[type="number"]').on('blur', function() {
		$(this).val(parseFloat($(this).val()));
		if (isNaN($(this).val())) {
			$(this).val('');
		}
		if ($(this).attr('min')) {
			VAL_HELPER.checkMin($(this));
		}
		if ($(this).attr('max')) {
			VAL_HELPER.checkMax($(this));
		}
		if ($(this).attr('pattern')) {
			VAL_HELPER.checkPattern($(this));
		}
	});

	// Allow Number only
	$('input[type="number"]:not(.integer)').on('keydown', function(e){
		if( !(e.keyCode == 8                                	// backspace
			|| e.keyCode == 9                              	// tab
			|| e.keyCode == 17                              // ctrl
			|| e.keyCode == 46                              // delete
			|| (e.keyCode >= 35 && e.keyCode <= 40)     // arrow keys/home/end
			|| (e.keyCode >= 48 && e.keyCode <= 57)     // numbers on keyboard
			|| (e.keyCode >= 96 && e.keyCode <= 105)   // number on keypad
			|| (e.keyCode == 190))	// Period
			) {
				e.preventDefault();     // Prevent character input
		}
		var val = $(this).val();
		if (e.keyCode == 190 && ( ! val || /[\.]/g.test(val) )) {
			e.preventDefault();     // Prevent character input
		}
	});

	$('input[type="number"].integer').on('keydown', function(e){
		if( !(e.keyCode == 8                                	// backspace
			|| e.keyCode == 9                              	// tab
			|| e.keyCode == 17                              // ctrl
			|| e.keyCode == 46                              // delete
			|| (e.keyCode >= 35 && e.keyCode <= 40)     // arrow keys/home/end
			|| (e.keyCode >= 48 && e.keyCode <= 57)     // numbers on keyboard
			|| (e.keyCode >= 96 && e.keyCode <= 105))   // number on keypad
			) {
				e.preventDefault();     // Prevent character input
		}
		if ($(this).attr('min')) {
			VAL_HELPER.checkMin($(this));
		}
		if ($(this).attr('max')) {
			VAL_HELPER.checkMax($(this));
		}
	});
});