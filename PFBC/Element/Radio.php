<?php
namespace PFBC\Element;

class Radio extends \PFBC\OptionElement {
	protected $_attributes = array("type" => "radio");
	protected $inline;

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
		$labelClass = $this->_attributes["type"];
		if(!empty($this->inline))
			$labelClass .= " inline";

		$count = 0;
		foreach($this->options as $value => $text) {
			$value = $this->getOptionValue($value);

			echo '<label class="', $labelClass . '"> <input id="', $this->_attributes["id"], '-', $count, '"', $this->getAttributes(array("id", "value", "checked", "data-bind-div", "data-bind-label")), ' value="', $this->filter($value), '"';
			if(isset($this->_attributes["value"]) && $this->_attributes["value"] == $value)
				echo ' checked="checked"';
			echo '/> ', $text, ' </label> ';
			++$count;
		}
	}
}
