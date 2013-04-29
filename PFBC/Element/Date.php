<?php
namespace PFBC\Element;

class Date extends Textbox {
	protected $_attributes = array(
		"type" => "date",
		"pattern" => "\d{2}/\d{2}/\d{4}"
	);

	public function __construct($label, $name, array $properties = null) {
		$this->_attributes["placeholder"] = "mm/dd/yyyy";
		$this->_attributes["title"] = $this->_attributes["placeholder"];

		parent::__construct($label, $name, $properties);
    }

	public function render() {
		$this->validation[] = new \PFBC\Validation\RegExp("/" . $this->_attributes["pattern"] . "/", "Error: The %element% field must match the following date format: " . $this->_attributes["title"]);
		parent::render();
	}

	function jQueryDocumentReady() {
		$options = '{
		changeMonth: true,
		changeYear: true
		}';
		echo 'jQuery("#', $this->_attributes["id"], '").datepicker('.$options.');';
	}

	function getJSFiles() {
		return array(
			$this->_form->getResourcesPath() . "jquery-ui/js/jquery-ui.min.js"
		);
	}

	public function getCSSFiles() {
		return array(
			$this->_form->getResourcesPath() . "jquery-ui/css/smoothness/jquery-ui.min.css"
		);
	}
}
