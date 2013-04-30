<?php
namespace PFBC\Element;

class Date extends Textbox {
	protected $_attributes = array(
		"type" => "date",
		"pattern" => "(0[1-9]|1[012])[/](0[1-9]|[12][0-9]|3[01])[/](19|20)\d\d"
	);
	protected $jQueryOptions;

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
		if(is_null($this->jQueryOptions))
		{
			$options = array(
				'changeMonth' => 'true',
				'changeYear' => 'true',
				'dateFormat' => "mm/dd/yy"
			);
			$this->jQueryOptions = $options;
		}
		echo 'jQuery("#', $this->_attributes["id"], '").datepicker(', $this->jQueryOptions(), ');';
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
