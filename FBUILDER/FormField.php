<?php
namespace FBUILDER;

require_once(dirname(__FILE__).'/../FirePHPCore/FirePHP.class.php');

class FormField {
	private $name;
	private $type;
	private $description;
	private $validation;
	private $settings;
	private $values;
	private $cssclass;
	private $attributes;
	private $id;
	private $class;
	private $style;
	private $options;		// Other Fields
	private $config;		// This is select, options, checkbox

	private $firephp;

	function __construct($init = array())
	{
		$this->firephp = \FirePHP::getInstance(true);

		// Override the internal variables
		//$this->firephp->log('=== Init ===');
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

	protected function formatAttributes()
	{
		$options = array();
		foreach($this as $k => $v)
		{
			if(is_null($v) || $k === 'firephp')
			{
				continue;
			}
			else if(is_object($v))
			{
				//$this->firephp->log('=====');
				//$this->firephp->log($k);
				//$this->firephp->log($v);
				foreach($v as $obj_k => $obj_v)
				{
					$options[strtolower($obj_k)] = $obj_v;
				}
			}
			else
			{
				switch($k)
				{
					case 'name':
					case 'description':
					case 'featured':
						break;

					case 'cssclass':
						$options['class'] = $v;
						break;

					case 'type':
						switch($this->type)
						{
							case 'TextBox':
								$this->type = 'Textbox';
								break;

							case 'SelectMulti':
								$this->type = 'Checkbox';
								break;

							case 'SelectSingle':
								$this->type = 'Select';

							case 'Select':
							case 'Radio':
							case 'CheckboxOnly':
								$this->config = (is_null($this->config)) ? array(): $this->config;
								break;

							case 'DateInput':
								$this->type = 'Date';
								break;

						}
						break;

					default:
						$options[$k] = $v;
						break;
				}
			}
		}

		//$this->firephp->log($options);
		//$this->firephp->log($this->type);

		//var_dump('=========');
		//var_dump($options);
		if(isset($options['required']))
		{
			$options['required'] = filter_var($options['required'], FILTER_VALIDATE_BOOLEAN);
			if($options['required'] === FALSE)
			{
				unset($options['required']);
			}
		}
		//var_dump($options);

		if( ! is_null($this->config) )
		{
			if( ! empty($options['values']))
			{
				$options['options'] = $options['values'];
				unset($options['values']);
			}
			$this->config = array_merge((array)$this->config, $options);
		}
		else
		{
			$this->options = array_merge((array)$this->options, $options);
		}
	}
}