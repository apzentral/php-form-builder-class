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
			$text = $this->_attributes['value'];
			$tmp = $this->_attributes;

			foreach($this->_attributes as $k => $attr)
			{
				switch($k)
				{
					case'icon-left':
					case'icon-right':
						$text = (stripos($k, '-left') !== NULL) ? '<i class="'.$attr.'"></i> '.$text : $text.' <i class="'.$attr.'"></i>';
					case 'render':
					case 'value':
						unset($this->_attributes[$k]);
						break;
				}
			}

			echo '<button', $this->getAttributes(), '>'.$text.'</button>';
			$this->_attributes = $tmp;
		}
		else
		{
			echo '<input', $this->getAttributes(), '/>';
		}
	}
}
