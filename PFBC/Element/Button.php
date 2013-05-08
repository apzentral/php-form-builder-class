<?php
namespace PFBC\Element;

class Button extends \PFBC\Element {
	protected $_attributes = array("type" => "submit", "value" => "Submit");
	protected $icon;

	public function __construct($label = "Submit", $type = "", array $properties = null) {
		if(!is_array($properties))
			$properties = array();

		if(!empty($type))
			$properties["type"] = $type;

		$class = (empty($properties["class"])) ? 'btn' : $properties["class"];

		if((empty($type) || $type == "submit") && stripos($class, 'btn-primary') === NULL)
			$class .= " btn-primary";

		if(empty($properties["value"]))
			$properties["value"] = $label;

		parent::__construct("", "", $properties);
	}
}
