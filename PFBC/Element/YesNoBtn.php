<?php
namespace PFBC\Element;

class YesNoBtn extends Radio {
	protected $parentBind; // Attached event to parent
	protected $childBind; // Attached event to child
	protected $eventBind; // Attached event
	protected $jQueryOptions;

	public function __construct($label, $name, array $properties = null) {
		$options = array(
			"1" => "Yes",
			"0" => "No"
		);

		if(!is_array($properties))
			$properties = array("inline" => 1);
		elseif(!array_key_exists("inline", $properties))
			$properties["inline"] = 1;

		if(is_array($properties))
		{
			if(isset($properties['container_class']))
			{
				if(stripos($properties['container_class'], 'yes-no-btn') === FALSE)
				{
					$properties['container_class'] = $properties['container_class']. ' yes-no-btn';
				}
			}
			else
			{
				$properties['container_class'] = 'yes-no-btn';
			}
		}
		parent::__construct($label, $name, $options, $properties);
    }

	public function render() {
		$labelClass = $this->_attributes["type"];
		if(!empty($this->inline))
			$labelClass .= " inline yes-no";

		echo '<div class="btn-group" data-toggle-name="'.$this->_attributes["id"].'" data-toggle="buttons-radio" id="'.$this->_attributes["id"].'">';

		$required = (isset($this->_attributes['required'])) ? ' required': '';
		echo '<button data-toggle="button" class="btn yes" value="1" type="button">Yes</button>';
		echo '<button data-toggle="button" class="btn no" value="0" type="button">No</button>';
		echo '<input name="'.$this->_attributes['name'].'" type="hidden"'.$required.'>';

		echo '</div>';
	}

	function jQueryDocumentReady() {
		// Use attribute parent-bind for dynamic dom element
		if(is_null($this->parentBind))
		{
			echo 'jQuery("#', $this->_attributes["id"], ' > button").on("click", function() {
			var $parent = $(this).parent(),
			$input = $parent.find("input");
			$parent.find("button").removeClass("btn-success btn-danger");
			if($(this).val() === "1") {
				$(this).addClass("btn-success");
				$input.val(1);
			} else {
				$(this).addClass("btn-danger");
				$input.val(0);
			}
			});';
		}
		else
		{
			$child_target = (is_null($this->childBind)) ? '#'.$this->_attributes['id']: $this->childBind;
			echo 'jQuery("',$this->parentBind,'").on("'.$this->eventBind.'", "',$child_target,'", function() { jQuery("#', $this->_attributes["id"], '").removeClass("hasDatepicker").datepicker(', $this->jQueryOptions(), ');});';
		}
	}

	public function getCSSFiles() {
		return array(
			$this->_form->getResourcesPath() . "bootstrap-switch/css/bootstrapSwitch.css",
			$this->_form->getResourcesPath() . "bootstrap-switch/css/switchOverride.css",
		);
	}

	public function getJSFiles() {
		return array(
			$this->_form->getResourcesPath() . "bootstrap-switch/js/bootstrapSwitch.js"
		);
	}
}
