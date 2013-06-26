<?php
namespace PFBC\Validation;

class Audio extends \PFBC\Validation {
	protected $message = "Error: %element% is not a valid audio file.";
	protected $name;

	public function __construct($name, $message = "") {
		$this->name = $name;
		parent::__construct($message);
	}

	public function isValid($value) {
		if( $this->isNotApplicable($value) || stripos($this->getMimeType($_FILES[$this->name]['tmp_name']),'audio') !== FALSE )
			return true;
		return false;
	}
}
