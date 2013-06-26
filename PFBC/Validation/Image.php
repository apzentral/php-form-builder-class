<?php
namespace PFBC\Validation;

class Image extends \PFBC\Validation {
	protected $message = "Error: %element% is not a valid image file.";
	protected $name;

	public function __construct($name, $message = "") {
		$this->name = $name;
		parent::__construct($message);
	}

	public function isValid($value) {
		if($this->isNotApplicable($value) || \exif_imagetype($_FILES[$this->name]['tmp_name']))
			return true;
		return false;
	}
}
