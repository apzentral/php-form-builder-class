<?php
namespace PFBC\Element;

class FullName extends Textbox {
	protected $middle_name;

	public function render() {

		if( isset($this->_attributes['middlename']) )
		{
			$this->middle_name = $this->_attributes['middlename'];
		}

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

		$validation_name = ucwords($this->_attributes['name'])."'s ";

		//$this->debug_data($new_attr);
		//var_dump($this->_attributes);
		$required = (isset($this->_attributes['required'])) ? ' required': '';
		echo '<div class="row-fluid">';
		echo '<div class="span4"><input', $new_attr, ' placeholder="First Name" name="'.$this->_attributes['name'].'_fullname_first_name" id="'.$this->_attributes['name'].'_fullname_first_name"'.$required.' data-validation-name="'.$validation_name.'First Name"/></div>';
		if( is_null($this->middle_name) || $this->middle_name )
		{
			echo '<div class="span4"><input', $new_attr, ' placeholder="Middle Initial" name="'.$this->_attributes['name'].'_fullname_middle_name" id="'.$this->_attributes['name'].'_fullname_middle_name" data-validation-name="'.$validation_name.'Middle Initial"/></div>';
		}
		echo '<div class="span4"><input', $new_attr, ' placeholder="Last Name" name="'.$this->_attributes['name'].'_fullname_last_name" id="'.$this->_attributes['name'].'_fullname_last_name"'.$required.' data-validation-name="'.$validation_name.'Last Name"/></div>';
		//var_dump($this->middle_name);
		//var_dump(is_null($this->middle_name) || $this->middle_name);
		echo '</div>';

		$this->renderAddOn("append");

		if(!empty($addons))
			echo '</div>';
	}
}
