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
	$('body').on('change', 'input[type="number"], input.number', function() {VAL_HELPER.validateFloatEvent($(this))});

	// Allow Number only
	$('body').on('keydown', 'input[type="number"]:not(.integer)', function(e){
		if( !(e.which == 8                                	// backspace
			|| e.which == 9                              	// tab
			|| e.which == 17                              // ctrl
			|| e.which == 46                              // delete
			|| (e.which >= 35 && e.which <= 40)     // arrow keys/home/end
			|| (e.which >= 48 && e.which <= 57)     // numbers on keyboard
			|| (e.which >= 96 && e.which <= 105)   // number on keypad
			|| (e.which == 190)	// Period
			|| (e.which == 110))	// Decimal Point
			) {
				e.preventDefault();     // Prevent character input
		}
		var val = $(this).val();
		if ( (e.which == 190 || e.which == 110) && ( ! val || /[\.]/g.test(val) )) {
			e.preventDefault();     // Prevent character input
		}
	});

	$('body').on('keydown', 'input[type="number"].integer, input.integer', function(e){
		if( !(e.which == 8                                	// backspace
			|| e.which == 9                              	// tab
			|| e.which == 17                              // ctrl
			|| e.which == 46                              // delete
			|| (e.which >= 35 && e.which <= 40)     // arrow keys/home/end
			|| (e.which >= 48 && e.which <= 57)     // numbers on keyboard
			|| (e.which >= 96 && e.which <= 105))   // number on keypad
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

	/**
	 * Convert to upppercase only
	 */
	$('body').on('keyup', ':input.uppercase', function(e){
		$(this).val(($(this).val()).toUpperCase());
	});
});