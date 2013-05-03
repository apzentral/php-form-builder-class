<?php
namespace PFBC\Element;

class BloodPressure extends Textbox {
	protected $_attributes = array(
		"type" => "number",
		"step" => "any",
		"min" => 0,
		"max" => 500
	);

	public function render() {
		$addons = array();
		if(!empty($this->prepend))
			$addons[] = "input-prepend";
		if(!empty($this->append))
			$addons[] = "input-append";
		if(!empty($addons))
			echo '<div class="', implode(" ", $addons), '">';

		$this->renderAddOn("prepend");

		if(isset($this->attributes["value"]) && is_array($this->attributes["value"]))
			$this->attributes["value"] = "";

		//$this->debug_data($this->getAttributes());

		unset($this->_attributes['data-validation-name']);
		$attr = explode(' ', $this->getAttributes());

		$new_attr = '';
		foreach($attr as $v)
		{
			$attr_tmp = explode('=', $v);

			if(count($attr_tmp) !== 2)
			{
				continue;
			}

			switch($attr_tmp[0])
			{
				case 'id':
				case 'name':
					break;

				default:
					$new_attr .= " {$attr_tmp[0]}={$attr_tmp[1]}";
			}
		}

		//$this->debug_data($new_attr);
		//var_dump($this->_attributes);
		$required = (isset($this->_attributes['required'])) ? ' required': '';
		echo '<div class="row-fluid">';
		echo '<div class="span12 blood-pressure"><input', $new_attr, ' placeholder="Systolic" name="'.$this->_attributes['name'].'_systolic" id="'.$this->_attributes['name'].'_systolic"'.$required.' data-validation-name="Systolic"/> / ';
		echo '<input', $new_attr, ' placeholder="Diastolic" name="'.$this->_attributes['name'].'_diastolic" id="'.$this->_attributes['name'].'_diastolic"'.$required.' data-validation-name="Diastolic"/></div>';
		echo '</div>';

		$this->renderAddOn("append");

		if(!empty($addons))
			echo '</div>';
	}
}
