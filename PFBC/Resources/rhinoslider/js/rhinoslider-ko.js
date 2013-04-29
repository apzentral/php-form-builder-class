//===== KO Setup for FormWizard =====//
$(function(){
	var RHINO_FORM = {
		// Property
		currentFieldset : 0,

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
			obj.one("click", function() {
				RHINO_FORM.setBackButton(obj);
			});
		},
		setValidation : function(obj) {
			var currentFieldset = this.getCurrentFieldset(obj);
			if (currentFieldset === 0) {
				obj.prev().prev().show({duration:500});
			}
			if (currentFieldset === (form_wizard.name.length-2)) {
				obj.text('Send');
			}
			if (this.validateFields()) {
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
			}
			obj.one("click", function() {
				RHINO_FORM.setValidation(obj);
			});
		},
		validateFields : function() {
			// Method to validate fields
			return true;
		},
		showRequest : function(formData, jqForm, options) {
			// formData is an array; here we use $.param to convert it to a string to display it
			// but the form plugin does this for you automatically when it submits the data
			var queryString = $.param(formData);

			// jqForm is a jQuery object encapsulating the form element.  To access the
			// DOM element for the form do this:
			// var formElement = jqForm[0];

			alert('About to submit: \n\n' + queryString);

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

			alert('status: ' + statusText + '\n\nresponseText: \n' + responseText + '\n\nThe output div should have already been updated with the responseText.');
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

	$(".rhino-prev").one("click", function() {
		RHINO_FORM.setBackButton($(this));
	});
	$(".form-submit").one("click", function() {
		RHINO_FORM.setValidation($(this));
	});
});
