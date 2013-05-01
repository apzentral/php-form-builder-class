<?php
namespace PFBC\Element;

class YesNoMaybe extends Radio {
	public function __construct($label, $name, array $properties = null) {
		$options = array(
			"1" => "Yes",
			"0" => "No",
			'-1' => "Unknown"
		);

		if(!is_array($properties))
			$properties = array("inline" => 1);
		elseif(!array_key_exists("inline", $properties))
			$properties["inline"] = 1;

		parent::__construct($label, $name, $options, $properties);
    }

	public function render() {
		$labelClass = $this->_attributes["type"];
		if(!empty($this->inline))
			$labelClass .= " inline yes-no";

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
