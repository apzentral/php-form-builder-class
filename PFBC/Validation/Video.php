<?php
namespace PFBC\Validation;

class Video extends \PFBC\Validation {
	protected $message = "Error: %element% is not a valid video file.";
	protected $name;

	public function __construct($name, $message = "") {
		$this->name = $name;
		parent::__construct($message);
	}

	public function isValid($value) {
		if( $this->isNotApplicable($value) || stripos($this->getMimeType($_FILES[$this->name]['tmp_name']),'video') !== FALSE )
			return true;
		return false;
	}
}
