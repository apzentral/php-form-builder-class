<?php
namespace PFBC\Element;

class Phone extends Textbox {
	protected $_attributes = array(
		"type" => "tel"
	);

	public function __construct($label, $name, array $properties = null) {
		$this->_attributes["placeholder"] = "(XXX) XXX-XXXX";
		$this->_attributes["pattern"] = "\(\d{3}\) \d{3}[\-]\d{4}";
		$this->_attributes["data-format"] = "(ddd) ddd-dddd";
		$this->_attributes["class"] = "bfh-phone";

		parent::__construct($label, $name, $properties);
    }
}
