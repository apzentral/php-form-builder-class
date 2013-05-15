<?php
namespace PFBC\Element;

class Select extends \PFBC\OptionElement {
	protected $_attributes = array();

	public function __construct($label, $name, array $options, array $properties = null)
	{
		if(isset($options['options']))
		{
			$this->options = $options['options'];
			$tmp_array = array();
			foreach($options as $k => $v)
			{
				switch($k)
				{
					case 'options':
						break;

					default:
						$tmp_array[$k] = $v;
				}
			}

			if( ! empty($tmp_array))
			{
				$properties = $tmp_array;
			}
		}
		else
		{
			$this->options = $options;
		}

		if(!empty($this->options) && array_values($this->options) === $this->options)
			$this->options = array_combine($this->options, $this->options);

		parent::__construct($label, $name, $this->options, $properties);
	}

	public function render() {
		if(isset($this->_attributes["value"])) {
			if(!is_array($this->_attributes["value"]))
				$this->_attributes["value"] = array($this->_attributes["value"]);
		}
		else
			$this->_attributes["value"] = array();

		if(!empty($this->_attributes["multiple"]) && substr($this->_attributes["name"], -2) != "[]")
			$this->_attributes["name"] .= "[]";

		echo '<select', $this->getAttributes(array("value", "selected", "data-bind-div", "data-bind-label")), '>';
		$selected = false;
		foreach($this->options as $value => $text) {
			$value = $this->getOptionValue($value);
			echo '<option value="', $this->filter($value), '"';
			if(!$selected && in_array($value, $this->_attributes["value"])) {
				echo ' selected="selected"';
				$selected = true;
			}
			echo '>', $text, '</option>';
		}
		echo '</select>';
	}
}
