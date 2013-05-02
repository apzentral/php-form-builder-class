<?php
namespace PFBC\Element;

class Zipcode extends Textbox {
	protected $_attributes = array(
		"type" => "number"
	);
	protected $country;

	public function __construct($label, $name, array $properties = null) {
		$this->country = 'US';

		parent::__construct($label, $name, $properties);
		switch($this->country)
		{

			case 'US':
			default:
				$this->_attributes["placeholder"] = "XXXXX";
				$this->_attributes["pattern"] = "\d{5}";
				$this->_attributes['maxlength'] = 5;
				break;
		}
    }
}
