/*===== jQuery Validation to check for the input =====*/
$(function(){
	// Allow Number only
	$('input[type="number"]').on('keydown', function(e){
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


});