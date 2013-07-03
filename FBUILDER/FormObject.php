<?php
namespace FBUILDER;

class FormObject {
	private $name;
	private $title;
	private $language;
	private $fields;
	private $view;
	private $formoptions;
	private $viewoptions;
	private $submitbutton;
	private $resetbutton;

	function __construct($init = array())
	{
		// Override the internal variables
		foreach($init as $k => $v)
		{
			$k = strtolower($k);
			$this->{$k} = $v;
		}

		$this->formatAttributes();
	}

	public function __get($property) {
		if (property_exists($this, $property)) {
			return $this->$property;
		}
	}

	public function __set($property, $value) {
		if (property_exists($this, $property)) {
			$this->$property = $value;
		}

		return $this;
	}

	public function formatAttributes()
	{
		$array = array();
		if( ! is_null($this->formoptions) )
		{
			foreach($this->formoptions as $k => $v)
			{
				switch($k)
				{
					case 'CssClass':
						$array['class'] = $v;
						break;

					case 'Path':
						$array['resourcesPath'] = $v;
						break;

					case 'SubmitButton':
					case 'ResetButton':
						$this->{strtolower($k)} = $v;
						break;

					default:
						$array[strtolower($k)] = $v;
						break;
				}
			}
		}
		$this->formoptions = $array;
	}
}