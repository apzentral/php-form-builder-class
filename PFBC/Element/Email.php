<?php
namespace PFBC\Element;

class Email extends Textbox {
	protected $_attributes = array(
		"type" => "email",
		"placeholder" => "your-email@email.com",
		"pattern" => "^[a-zA-Z0-9_.+-]+@[a-zA-Z0-9-]+\.[a-zA-Z0-9-.]+$",
		"email_server" => array(
			"interact.ccsd.net",
			"cchd.org",
			"gmail.com",
			"hotmail.com",
			"yahoo.com"
		)
	);

	public function render() {
		//var_dump($this->_attributes);

		$this->validation[] = new \PFBC\Validation\Email;

		if( isset($this->_attributes['autocomplete']) || isset($this->_attributes['defaultemail']) )
		{
			$this->renderAutoComplete();
		}
		else
		{
			parent::render();
		}
	}

	public function renderAutoComplete()
	{
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
				case 'defaultemail':
				case 'placeholder':
				case 'email_server':
					break;

				case 'pattern':
					$attr_tmp[1] = str_replace('"', '', $attr_tmp[1]);
					$pattern_array = explode('@', $attr_tmp[1]);
					break;
				default:
					$new_attr .= " {$attr_tmp[0]}={$attr_tmp[1]}";
			}
		}

		//$this->debug_data($new_attr);
		//var_dump($this->_attributes);
		$required = (isset($this->_attributes['required'])) ? ' required': '';

		$autocomplete = '';

		if( isset($this->_attributes['autocomplete']) )
		{
			$autocomplete = ' data-minlength="2" data-provide="typeahead" autocomplete="off" data-source=\'[';
			foreach($this->_attributes['email_server'] as $v)
			{
				$autocomplete .= '"'.$v.'",';
			}
			$autocomplete = substr($autocomplete, 0, -1);
			$autocomplete .= ']\'';
		}

		echo '<input '.$new_attr.' placeholder="Email" name="'.$this->_attributes['name'].'_email_id" id="'.$this->_attributes['name'].'_email_id"'.$required.' data-validation-name="Email" pattern="'.$pattern_array[0].'$"/>';
		echo ' <span class="add-on">&#64;</span> ';
		echo '<input '.$new_attr.' placeholder="Email.com" name="'.$this->_attributes['name'].'_email_server" id="'.$this->_attributes['name'].'_email_server"'.$required.' data-validation-name="Email.com"'.$autocomplete.' pattern="^'.$pattern_array[1].'"/>';

		$this->renderAddOn("append");

		if(!empty($addons))
			echo '</div>';
	}
}
