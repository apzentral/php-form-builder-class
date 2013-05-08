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

	// Allow button tag to render as a button
	public function render() {
		if(isset($this->attributes["value"]) && is_array($this->attributes["value"]))
			$this->attributes["value"] = "";

		// If user want to render as button instead of input it is ok!
		if(isset($this->_attributes['render']) && $this->_attributes['render'] === 'button')
		{
			echo '<button', $this->getAttributes(), '/>';
		}
		else
		{
			echo '<input', $this->getAttributes(), '/>';
		}
	}
}
