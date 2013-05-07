//===== KO Setup for FormWizard =====//
jQuery(document).ready(function($) {
	var FBUILDER = {
		self: this,
		// Methods
		getCurrentFieldset : function(obj) {
			return parseInt(obj.parentsUntil('.rhino-form-wrapper', '.rhino-container').find('.rhino-active').attr("id").match(/\d+/g));
		},
		setBackButton : function(obj) {
			var currentFieldset = this.getCurrentFieldset(obj);
			obj.one("click.fbuilder", function() {
				FBUILDER.setBackButton(obj);
			});
			var obj_parent = obj.parentsUntil('.rhino-form-wrapper', '.rhino-container');
			var previous_bullet = obj_parent.find('.rhino-active-bullet');
			var current_bullet = $('#rhino-item'+(currentFieldset-1)+'-bullet', obj_parent);
			var result = FBUILDER.validateFields(obj, false);
			if (result) {
				previous_bullet.off('click');
				FBUILDER.clickBullet(previous_bullet, obj, obj_parent);
				current_bullet.removeClass('step-success').addClass('rhino-active-bullet');
				obj.prev().prev().trigger('click');
			} else {
				return false;
			}
		},
		setValidation : function(obj) {
			var currentFieldset = this.getCurrentFieldset(obj);

			// Validate Current Fields
			if (this.validateFields(obj)) {
				if (currentFieldset === (form_wizard.name.length-1)) {
					// Send form
					var options = {
						//target: '#modal-body-text',
						beforeSubmit: this.showRequest,
						success: this.showResponse
					};
					obj.parentsUntil('.rhino-form-wrapper', '.form-horizontal').ajaxSubmit(options);
				}
				else {
					obj.prev().prev().trigger('click');
				}
				if (currentFieldset === 0) {
					obj.prev().show({duration:500});
				}
			}
			obj.one("click.fbuilder", function() {
				FBUILDER.setValidation(obj);
			});
		},
		validateFields : function(obj, setClickE, checkBtn) {
			setClickE = typeof setClickE === 'undefined' ? true : setClickE;
			checkBtn = typeof checkBtn === 'undefined' ? true : checkBtn;
			// Method to validate fields
			var currentFieldset = this.getCurrentFieldset(obj);
			var error = false;
			var first_obj = null;
			var obj_parent = obj.parentsUntil('.rhino-form-wrapper', '.rhino-container');
			var current_form = obj_parent.find('.slider .rhino-active');
			var current_bullet = obj_parent.find('.rhino-active-bullet');

			$('#form-modal .modal-header').removeClass('alert-success').addClass('alert-error');
			$('#form-modal #modal-title').removeClass('text-success').text('Please correct the following errors');
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

			//error = false;	// Debug
			if (setClickE) {
				FBUILDER.clickBullet(current_bullet, obj, obj_parent);
			}

			if (error) {
				current_bullet.removeClass("step-success").addClass("step-error");
				first_obj.addClass('first-error');
				$('#form-modal').modal('show');
			} else {
				current_bullet.removeClass("step-error").addClass("step-success");

				if (checkBtn) {
					if (obj.hasClass('form-submit')) {
						$('#rhino-item'+(currentFieldset+1)+'-bullet', obj_parent).removeClass("step-success");
						if (currentFieldset === (form_wizard.name.length-2)) {
							obj.text('Send');
						}
					} else if(obj.hasClass('form-prev')) {
						if (currentFieldset === 1) {
							obj.hide({duration:500});
						}
						if (currentFieldset > 1) {
							obj.show({duration:500});
						}
						if (currentFieldset <= (form_wizard.name.length-1)) {
							obj.next().text('Next');
						}
					}
				}
			}
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
		clickBullet : function(current_bullet, obj, obj_parent) {
			current_bullet.one('click', function() {
				var result = FBUILDER.validateFields(obj, true, false);
				if (result) {
					current_bullet.removeClass('step-success');
					var currentField = parseInt(current_bullet.attr('id').match(/\d+/g));
					if (currentField === (form_wizard.name.length-1)) {
						$('.form-submit', obj_parent).text('Send');
					} else if (currentField === 0) {
						$('.form-prev', obj_parent).hide({duration:500});
					} else if (currentField < (form_wizard.name.length-1)) {
						$('.form-prev', obj_parent).show({duration:500});
						$('.form-submit', obj_parent).text('Next');
					}
				} else {
					FBUILDER.clickBullet(current_bullet, obj, obj_parent);
				}
			});
			obj_parent.find('.slider').data('rhinoslider').addClickBullet(current_bullet);
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

	$(".form-prev").one("click.fbuilder", function() {
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
