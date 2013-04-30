<?php
namespace PFBC\Element;

class BDate extends Textbox {
	protected $jQueryOptions;

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


		echo '<div class="row-fluid">';
		echo '<div class="span12"><div '.$this->getAttributes().'></div></div>';
		echo '</div>';

		$this->renderAddOn("append");

		if(!empty($addons))
			echo '</div>';
	}

	function getJSFiles() {
		return array(
			$this->_form->getResourcesPath() . "validation/bday-picker.min.js"
		);
	}

	function jQueryDocumentReady() {
		echo 'jQuery("#', $this->_attributes["id"], '").birthdaypicker(', $this->jQueryOptions(), ');';
	}
}
