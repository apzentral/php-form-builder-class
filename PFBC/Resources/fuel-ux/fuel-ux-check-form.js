/*===== Check Form in Fuel UX =====*/

jQuery(document).ready(function($) {

	FUEL_UX_FORM.$WIZARD_BOX = FUEL_UX_FORM.$WIZARD.parent();
	FUEL_UX_FORM.checkCurrentPage = function() {
		return FUEL_UX_FORM.$WIZARD.wizard('selectedItem').step;
	};
	FUEL_UX_FORM.getjQCurrentPage = function() {
		return $('#step'+FUEL_UX_FORM.checkCurrentPage(), FUEL_UX_FORM.$WIZARD_BOX);
	};

	function validateError($obj) {

	}

	// Attached all the events
	FUEL_UX_FORM.$WIZARD.on('changed', function(e) {
		var current_page = FUEL_UX_FORM.getjQCurrentPage(),
			field_error = false,
			field_name = '',
			error_message = null,
			regex = null;

		//console.log('changed');
		//console.log(FUEL_UX_FORM.checkCurrentPage());
		//console.log(current_page);
		//console.log($('fieldset :input', current_page));

		$('fieldset :input', current_page).each(function() {
			field_name = $(this).attr('data-validation-name') || '';
			$(this).removeClass('field-error');
			console.log(field_name);
		});
	});

	//FUEL_UX_FORM.$WIZARD.on('stepclick', function(e) {
	//	console.log('stepclick');
	//	console.log(FUEL_UX_FORM.checkCurrentPage());
	//});

	FUEL_UX_FORM.$WIZARD.on('finished', function(e) {
		var current_page = FUEL_UX_FORM.getjQCurrentPage();
		//console.log('finished');
		//console.log(FUEL_UX_FORM.checkCurrentPage());
		//console.log(current_page);
	});
});
