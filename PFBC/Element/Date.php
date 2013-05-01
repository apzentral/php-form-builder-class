<?php
namespace PFBC\Element;

class Date extends Textbox {
	protected $_attributes = array(
		"type" => "date",
		"pattern" => "(0[1-9]|1[012])[/](0[1-9]|[12][0-9]|3[01])[/](19|20)\d\d$"
	);
	protected $parentBind; // Attached event to parent
	protected $childBind; // Attached event to child
	protected $eventBind; // Attached event
	protected $jQueryOptions;

	public function __construct($label, $name, array $properties = null) {
		$this->_attributes["placeholder"] = "mm/dd/yyyy";
		$this->_attributes["title"] = $this->_attributes["placeholder"];

		$this->eventBind = "click";
		parent::__construct($label, $name, $properties);
		$options = array(
			'changeMonth' => 'true',
			'changeYear' => 'true',
			'dateFormat' => "mm/dd/yy"
		);
		$this->jQueryOptions = array_merge((array)$this->jQueryOptions, $options);
    }

	public function render() {
		$this->validation[] = new \PFBC\Validation\RegExp("/" . $this->_attributes["pattern"] . "/", "Error: The %element% field must match the following date format: " . $this->_attributes["title"]);
		parent::render();
	}

	function jQueryDocumentReady() {
		// Use attribute parent-bind for dynamic dom element
		if(is_null($this->parentBind))
		{
			echo 'jQuery("#', $this->_attributes["id"], '").datepicker(', $this->jQueryOptions(), ');';
		}
		else
		{
			$child_target = (is_null($this->childBind)) ? '#'.$this->_attributes['id']: $this->childBind;
			echo 'jQuery("',$this->parentBind,'").on("'.$this->eventBind.'", "',$child_target,'", function() { jQuery("#', $this->_attributes["id"], '").removeClass("hasDatepicker").datepicker(', $this->jQueryOptions(), ');});';
		}
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
