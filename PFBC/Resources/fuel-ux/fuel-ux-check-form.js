/*===== Check Form in Fuel UX =====*/

jQuery(document).ready(function($) {

	FUEL_UX_FORM.$WIZARD_BOX = FUEL_UX_FORM.$WIZARD.parent();
	FUEL_UX_FORM.checkCurrentPage = function() {
		return FUEL_UX_FORM.$WIZARD.wizard('selectedItem').step;
	};
	FUEL_UX_FORM.getjQCurrentPage = function() {
		return $('#step'+FUEL_UX_FORM.checkCurrentPage(), FUEL_UX_FORM.$WIZARD_BOX);
	};

	var FBUILDER = {
		self: this,
		// Methods
		setValidation : function(e) {
			// Validate Current Fields
			//console.log(e);
			if (this.validateFields()) {
				if (e.type === 'finished') {
					// Send form
					var options = {
						//target: '#modal-body-text',
						beforeSubmit: this.showRequest,
						success: this.showResponse
					};
					//obj.parentsUntil('.fuelux', '.step-content').ajaxSubmit(options);
				}
				else {
					// Custom Event Before Click Next
					var $form = obj.parentsUntil('.fuelux', 'form');
					$form.trigger('beforeNextStep.fbuilder', [$form, currentFieldset]);
				}
			}
			else {
				// Not pass validation
				e.preventDefault();
			}
		},
		validateFields : function() {
			// Method to validate fields
			var currentFieldset = FUEL_UX_FORM.checkCurrentPage(),
			error = false,
			first_obj = null,
			current_form = FUEL_UX_FORM.getjQCurrentPage();

			$('#form-modal .modal-header').removeClass('alert-success').addClass('alert-error');
			$('#form-modal #modal-title').removeClass('text-success').text('Please correct the following errors');
			$("#form-modal #modal-body-text").html('');
			// Get all inputs dom
			$('form .controls.printed-error', current_form).removeClass('printed-error');
			$(":input", current_form).each(function(i){
				var result;
				$('body #'+$(this).attr('id')).off('blur');
				result = FBUILDER.checkAttr($(this), true);
				if ( ! error) {
					error = result;
				}
				if (first_obj === null && result) {
					first_obj = $(this);
				}
			});

			if (error) {
				first_obj.addClass('first-error');
				$('#form-modal').modal('show');
			}
			return (! error);
		},
		checkAttr: function(obj, printError) {
			printError = typeof printError !== 'undefined' ? printError : false;
			var field_error = false,
			field_name = obj.attr('data-validation-name') || '',
			error_message = null,
			regex = null;
			obj.val($.trim(obj.val()));	// trim val
			obj.removeClass('field-error');
			if (obj.attr('required') && (obj.val() === '' || ( obj.is(':checkbox') && ! obj.is(':checked') || obj.is(':radio'))) ) {
				field_error = true;
				if (printError) {
					if (obj.is(':radio')) {
						var _radio_container = obj.parents('.controls');
						if ($(':radio[name='+obj.attr('name')+']:checked', _radio_container).is(':checked')) {
							return;
						}
						if (_radio_container.hasClass('printed-error')) {
							return;
						} else {
							_radio_container.addClass('printed-error');
						}
					}
					if (obj.data('error-msg')) {
						error_message = (field_name==='') ? obj.data('error-msg'): field_name+' ' + obj.data('error-msg');
					} else {
						error_message = field_name + ' is required.';
					}
					FBUILDER.printError(obj, error_message);
				}
			}
			if ( ! field_error && (obj.attr('required') || obj.val() !== '') && obj.attr('pattern')) {
				regex = new RegExp(obj.attr('pattern'), 'g');
				if ( ! regex.test(obj.val())) {
					field_error = true;
					if (printError) {
						error_message = field_name + ' is invalid.';
						FBUILDER.printError(obj, error_message);
					}
				}
			}
			if (field_error) {
				obj.addClass('field-error');
			}
			return field_error;
		},
		// Build Error Dialog
		printError : function(obj, html) {
			$('body').on('blur', '#'+obj.attr('id'), function() {
				FBUILDER.checkAttr(obj, false);
			});
			$('#form-modal #modal-body-text').append('<p class="text-error">'+html+'</p>');
		},
		showRequest : function(formData, jqForm, options) {
			// formData is an array; here we use $.param to convert it to a string to display it
			// but the form plugin does this for you automatically when it submits the data
			var queryString = $.param(formData),
			result = true;

			// jqForm is a jQuery object encapsulating the form element.  To access the
			// DOM element for the form do this:
			// var formElement = jqForm[0];

			//alert('About to submit: \n\n' + queryString);

			// here we could return false to prevent the form from being submitted;
			// returning anything other than false will allow the form submit to continue

			// Fire Custom before Form Submit Event
			result = jqForm.trigger('beforeSubmit.fbuilder', [queryString]);
			return result;
		},
		showResponse : function(responseText, statusText, xhr, $form) {
			// for normal html responses, the first argument to the success callback
			// is the XMLHttpRequest object's responseText property

			// if the ajaxForm method was passed an Options Object with the dataType
			// property set to 'xml' then the first argument to the success callback
			// is the XMLHttpRequest object's responseXML property

			// if the ajaxForm method was passed an Options Object with the dataType
			// property set to 'json' then the first argument to the success callback
			// is the json data object returned by the server

			//alert('status: ' + statusText + '\n\nresponseText: \n' + responseText + '\n\nThe output div should have already been updated with the responseText.');

			// Fire Custom Event Before Complete Event
			$form.trigger('respond.fbuilder', [responseText, statusText, xhr]);
		}
	};

	// Attached all the events
	FUEL_UX_FORM.$WIZARD.on('change', function(e) {
		FBUILDER.setValidation(e);
	});

	//FUEL_UX_FORM.$WIZARD.on('stepclick', function(e) {
	//	console.log('stepclick');
	//	console.log(FUEL_UX_FORM.checkCurrentPage());
	//});

	FUEL_UX_FORM.$WIZARD.on('finished', function(e) {
		//var current_page = FUEL_UX_FORM.getjQCurrentPage();
		//console.log('finished');
		//console.log(FUEL_UX_FORM.checkCurrentPage());
		//console.log(current_page);
	});
});
