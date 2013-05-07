/*===== jQuery Validation to check for the input =====*/
jQuery(document).ready(function($) {
	var VAL_HELPER = {
		checkMin : function(obj) {
			if (obj.val() != '' && parseFloat(obj.attr('min')) > parseFloat(obj.val())) {
				obj.val(obj.attr('min'));
			}
		},
		checkMax : function(obj) {
			if (obj.val() != '' && parseFloat(obj.attr('max')) < parseFloat(obj.val())) {
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
		},
		validateFloatEvent : function(obj) {
			obj.val(parseFloat(obj.val()));
			if (isNaN(obj.val())) {
				obj.val('');
			}
			if (obj.attr('min')) {
				VAL_HELPER.checkMin(obj);
			}
			if (obj.attr('max')) {
				VAL_HELPER.checkMax(obj);
			}
			if (obj.attr('pattern')) {
				VAL_HELPER.checkPattern(obj);
			}
		}
	};

	// Check for Max and Min Attr
	$('input[type="number"], input.number').on({change : function() {VAL_HELPER.validateFloatEvent($(this))}});

	// Allow Number only
	$('input[type="number"]:not(.integer)').on('keydown', function(e){
		if( !(e.keyCode == 8                                	// backspace
			|| e.keyCode == 9                              	// tab
			|| e.keyCode == 17                              // ctrl
			|| e.keyCode == 46                              // delete
			|| (e.keyCode >= 35 && e.keyCode <= 40)     // arrow keys/home/end
			|| (e.keyCode >= 48 && e.keyCode <= 57)     // numbers on keyboard
			|| (e.keyCode >= 96 && e.keyCode <= 105)   // number on keypad
			|| (e.keyCode == 190)	// Period
			|| (e.keyCode == 110))	// Decimal Point
			) {
				e.preventDefault();     // Prevent character input
		}
		var val = $(this).val();
		if ( (e.keyCode == 190 || e.keyCode == 110) && ( ! val || /[\.]/g.test(val) )) {
			e.preventDefault();     // Prevent character input
		}
	});

	$('input[type="number"].integer, input.integer').on('keydown', function(e){
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