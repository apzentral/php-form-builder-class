<?php
namespace PFBC\Element;

use PFBC\Validation;

class File extends \PFBC\Element {
	protected $_attributes = array("type" => "file");

	public function __construct($label, $name, array $properties = null)
	{
		$this->setupValidation($properties, $name);
		parent::__construct($label, $name, $properties);
	}

	protected function setupValidation(&$properties, $name)
	{
		if( ! isset($properties['accept']) )
		{
			return;
		}

		// Syntax: accept="audio/*|video/*|image/*|MIME_type"
		switch($properties['accept'])
		{
			case 'audio/*':
				$validation = new Validation\Audio($name);
				break;

			case 'video/*':
				$validation = new Validation\Video($name);
				break;

			case 'image/*':
				$validation = new Validation\Image($name);
				break;
		}

		$properties['validation'] = (isset($properties['validation'])) ? array_merge($properties['validation'], $validation): $validation;

	}
}
