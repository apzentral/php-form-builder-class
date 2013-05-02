<?php
namespace PFBC\Element;

class Email extends Textbox {
	protected $_attributes = array(
		"type" => "email",
		"placeholder" => "your-email@email.com",
		"pattern" => "^[a-zA-Z0-9_.+-]+@[a-zA-Z0-9-]+\.[a-zA-Z0-9-.]+$"
	);

	public function render() {
		$this->validation[] = new \PFBC\Validation\Email;
		parent::render();
	}
}
