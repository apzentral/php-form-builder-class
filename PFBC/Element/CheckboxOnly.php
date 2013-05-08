<?php
namespace PFBC\Element;

class CheckboxOnly extends Checkbox {
	protected $columns;
	protected $required;

	public function __construct($label, $name, array $options, array $properties = null) {
		$this->columns = 3;

		$properties = (array)$properties;

		foreach($options as $k => $v)
		{
			if(property_exists($this, $k))
			{
				$this->{$k} = $v;
			}
			else
			{
				$properties[$k] = $v;
			}
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

		if(substr($this->_attributes["name"], -2) != "[]")
			$this->_attributes["name"] .= "[]";

		$labelClass = $this->_attributes["type"];
		if(!empty($this->inline))
			$labelClass .= " inline";

		$count = 0;
		$total_options = count($this->options);
		$options_col = $total_options / $this->columns;
		$options_left = $total_options % $this->columns;
		$bootstrap_col = 12 / $this->columns;
		$count_col = 0;

		$close_div = FALSE;

		if($options_left > 0)
		{
			$options_col++;
		}

		foreach($this->options as $value => $text) {
			$value = $this->getOptionValue($value);

			$required = ($this->required === NULL) ? '' : ' required';
			if($count % $options_col === 0 && $count_col < $this->columns)
			{
				echo '<div class="span'.$bootstrap_col.'">';
				echo '<div class="controls">';
				$count_col++;
				$close_div = TRUE;
			}

			echo '<label class="', $labelClass, '"> <input id="', $this->_attributes["id"], '-', $count, '"', $this->getAttributes(array("id", "value", "checked", "required", "data-bind-div", "data-bind-label")), ' value="', $this->filter($value), '"';
			if(in_array($value, $this->_attributes["value"]))
				echo ' checked="checked"';
			echo $required.'/> ', $text, ' </label> ';

			++$count;

			if($count % $options_col === 0 && $count_col < ($this->columns + 1))
			{
				echo '</div></div>';
				$close_div = FALSE;
			}
		}

		if($close_div)
		{
			echo '</div></div>';
		}
	}
}
