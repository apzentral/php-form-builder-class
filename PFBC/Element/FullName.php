<?php
namespace PFBC\Element;

class FullName extends Textbox {

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
		echo '<div class="span5"><input', $new_attr, ' placeholder="Last Name" name="fullname_last_name" id="fullname_last_name"'.$required.'/></div>';
		echo '<div class="span4"><input', $new_attr, ' placeholder="First Name" name="fullname_first_name" id="fullname_first_name"'.$required.'/></div>';
		echo '<div class="span3"><input', $new_attr, ' placeholder="Middle Initial" name="fullname_middle_name" id="fullname_middle_name"'.$required.'/></div>';
		echo '</div>';

		$this->renderAddOn("append");

		if(!empty($addons))
			echo '</div>';
	}
}
