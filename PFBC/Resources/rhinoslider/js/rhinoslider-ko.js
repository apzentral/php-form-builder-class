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
				obj.prev().trigger('click');
			}
			obj.one("click", function() {
				RHINO_FORM.setValidation(obj);
			});
		},
		validateFields : function() {
			// Method to validate fields
			return true;
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
