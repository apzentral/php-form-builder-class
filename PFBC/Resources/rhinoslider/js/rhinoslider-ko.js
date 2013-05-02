//===== KO Setup for FormWizard =====//
$(function(){
	var FBUILDER = {
		self: this,
		// Methods
		getCurrentFieldset : function(obj) {
			return parseInt(obj.parentsUntil('.rhino-form-wrapper', '.rhino-container').find('.rhino-active').attr("id").match(/\d+/g));
		},
		setBackButton : function(obj) {
			var currentFieldset = this.getCurrentFieldset(obj);
			if (currentFieldset === 1) {
				obj.hide({duration:500});
			}
			if (currentFieldset === (form_wizard.name.length-1)) {
				obj.next().next().text('Next');
			}
			obj.one("click.fbuilder", function() {
				FBUILDER.setBackButton(obj);
			});
		},
		setValidation : function(obj) {
			var currentFieldset = this.getCurrentFieldset(obj);
			if (currentFieldset === (form_wizard.name.length-2)) {
				obj.text('Send');
			}
			// Validate Current Fields
			if (this.validateFields(obj)) {
				if (currentFieldset === (form_wizard.name.length-1)) {
					// Send form
					var options = {
						target: '#respond-output',
						beforeSubmit: this.showRequest,
						success: this.showResponse
					};
					obj.parentsUntil('.rhino-form-wrapper', '.form-horizontal').ajaxSubmit(options);
				}
				else {
					obj.prev().trigger('click');
				}
				if (currentFieldset === 0) {
					obj.prev().prev().show({duration:500});
				}
			}
			obj.one("click.fbuilder", function() {
				FBUILDER.setValidation(obj);
			});
		},
		validateFields : function(obj) {
			// Method to validate fields
			var error = false;
			var first_obj = null;
			var obj_parent = obj.parentsUntil('.rhino-form-wrapper', '.rhino-container');
			var current_form = obj_parent.find('.slider .rhino-active');
			var current_bullet = obj_parent.find('.rhino-active-bullet');
			$('#form-modal #modal-title').text('Please correct the following errors');
			$("#form-modal #modal-body-text").html('');
			// Get all inputs dom
			$(":input", current_form).each(function(i){
				var result;
				$(this).off('blur');
				result = FBUILDER.checkAttr($(this), true);
				if ( ! error) {
					error = result;
				}
				if (first_obj === null && result) {
					first_obj = $(this);
				}
			});

			if (error) {
				$(current_bullet).removeClass("step-success").addClass("step-error");
				first_obj.addClass('first-error');
				$('#form-modal').modal('show');
			} else {
				$(current_bullet).removeClass("step-error").addClass("step-success");
			}
			//error = false;	// Debug
			return (! error);
		},
		checkAttr: function(obj, printError) {
			printError = typeof printError !== 'undefined' ? printError : false;
			var field_error = false;
			var field_name = (obj.data('validation-name')) ? obj.data('validation-name'): '';
			obj.val($.trim(obj.val()));	// trim val
			obj.removeClass('field-error');
			if (obj.attr('required') && obj.val() == '') {
				field_error = true;
				if (printError) {
					var error_message = field_name + ' is required.';
					FBUILDER.printError(obj, error_message);
				}
			}
			if ( ! field_error && (obj.attr('required') || obj.val() != '') && obj.attr('pattern')) {
				var regex = new RegExp(obj.attr('pattern'), 'g');
				if ( ! regex.test(obj.val())) {
					field_error = true;
					if (printError) {
						var error_message = field_name + ' is invalid.';
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
			obj.blur(function() {
				FBUILDER.checkAttr(obj, false);
			});
			$('#form-modal #modal-body-text').append('<p class="text-error">'+html+'</p>');
		},
		showRequest : function(formData, jqForm, options) {
			// formData is an array; here we use $.param to convert it to a string to display it
			// but the form plugin does this for you automatically when it submits the data
			var queryString = $.param(formData);

			// jqForm is a jQuery object encapsulating the form element.  To access the
			// DOM element for the form do this:
			// var formElement = jqForm[0];

			//alert('About to submit: \n\n' + queryString);

			// here we could return false to prevent the form from being submitted;
			// returning anything other than false will allow the form submit to continue
			return true;
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
		}
	};

	$('.slider').rhinoslider({
		controlsMousewheel: false,
		controlsPlayPause: false,
		controlsKeyboard: false,
		cycled: false,
		showBullets: 'always',
		showControls: 'always',
		slidePrevDirection: 'toRight',
		slideNextDirection: 'toLeft',
		prevText: 'Back',
		nextText: 'Next',
		bulletsClick: false
	});

	// Hide Button
	$(".rhino-prev").hide();
	$(".rhino-next").hide();

	$(".rhino-prev").one("click.fbuilder", function() {
		FBUILDER.setBackButton($(this));
	});
	$(".form-submit").one("click.fbuilder", function() {
		FBUILDER.setValidation($(this));
	});
	$('.rhino-btn').on("keyup.fbuilder", function(e) {
		if (e.keyCode === 13) {	// Enter Pressed
			$(this).trigger('click');
		}
	});

	// Modal Event
	$('#form-modal').on('hidden.fbuilder', function () {
		$('.slider .first-error').focus().removeClass('first-error');
    })
});
