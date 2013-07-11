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
		echo '<div class="span12"><div '.$this->getAttributes(array("data-bind-div", "data-bind-label")).'></div></div>';
		echo '</div>';

		$this->renderAddOn("append");

		if(!empty($addons))
			echo '</div>';
	}

	function getJSFiles() {
		return array(
			$this->_form->getResourcesPath() . "validation/bday-picker.js"
		);
	}

	function jQueryDocumentReady() {
		if( ! empty($this->jQueryOptions->minYear) )
		{
			$this->jQueryOptions->maxAge = date('Y') - $this->jQueryOptions->minYear;
			unset($this->jQueryOptions->minYear);
		}
		echo 'jQuery("#', $this->_attributes["id"], '").birthdaypicker( ', $this->jQueryOptions(), ' );';
	}
}
