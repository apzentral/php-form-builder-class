<?php
namespace PFBC\Element;

class Phone extends Textbox {
	protected $_attributes = array(
		"type" => "tel"
	);

	public function __construct($label, $name, array $properties = null) {
		$this->_attributes["placeholder"] = "(XXX) XXX-XXXX";

		parent::__construct($label, $name, $properties);
    }
}
