<?php
namespace FBUILDER;

use PFBC;
use PFBC\Form;
use PFBC\Element;
use PFBC\View;

require_once(dirname(__FILE__).'/../PFBC/Form.php');

function Load($class) {
	$file = __DIR__ . "/../" . str_replace("\\", DIRECTORY_SEPARATOR, $class) . ".php";
	if(is_file($file))
		include_once $file;
}
spl_autoload_register("FBUILDER\Load");
if(in_array("__autoload", spl_autoload_functions()))
	spl_autoload_register("__autoload");

class FormBuilder
{
	private $config;
	private $open_tag;
	private $options;

	function __construct($form_config = array(), $options = array())
	{
		// Config for PFBC\Form
		// FormSetup
		$this->config = array(
			"prevent" => array("bootstrap", "jQuery", "noConflict"),
			'resourcesPath' => '../PFBC/Resources/'
		);

		// Setup Options for FBUILDER
		// FormOptions
		$this->options = array(
			'label_seperator' => ':',
			'title_tag' => 'h3',
			'title_class' => 'title',
			'fieldset_tag' => 'h4',
			'fieldset_div' => FALSE
		);

		// Setup Tag keep track of what tag has been open.
		// Order Matter, Need to order from Column, Row, and Fieldset.
		// Depending on the precedance of the tag
		$this->open_tags = array(
			'Col' => FALSE,
			'Row' => FALSE,
			'Fieldset' => FALSE
		);

		// Override the internal variables
		if(is_array($form_config))
		{
			$this->config = array_merge($this->config, $form_config);
		}
		else
		{
			foreach($form_config as $k => $v)
			{
				$this->config[$k] = $v;
			}
		}
		if(is_array($options))
		{
			$this->options = array_merge($this->options, $options);
		}
		else
		{
			foreach($options as $k => $v)
			{
				$this->options[$k] = $v;
			}
		}
	}

	/**
	 * Will parse array string data into html form
	 *
	 * @parm	string	$data	string with json represent data
	 */
	public function parseFormSchema(FormObject $data, $option_params = array())
	{
		// Debug Data
		//var_dump($data);

		//===== Start Building the form =====//

		// Create a new Form
		$form = new Form($data->name);

		// Setup config
		if( isset($this->config['view']) )
		{
			$view = 'PFBC\\View\\'.$this->config['view'];
			$this->config['view'] = new $view;
		}
		$form->configure($this->config);

		// Create Title of this form
		//$form->addElement(new Element\HTML("<h3>{$data->title}</h3>"));

		// PFBC, option parameters
		$params = array(
			'fieldset' => FALSE
		);

		if(is_array($option_params))
		{
			$params = array_merge($params, $option_params);
		}
		else
		{
			foreach($option_params as $k => $v)
			{
				$params[$k] = $v;
			}
		}

		foreach($data->fields as $v)
		{
			if(get_class($v) !== 'FormField')
			{
				$v = new FormField($v);
			}

			if(is_object($v->options))
			{
				$tmp = array();
				foreach($v->options as $opt_k => $opt_v)
				{
					$tmp[$opt_k] = $opt_v;
				}
				$v->options = $tmp;
			}

			if(is_object($v->config))
			{
				$tmp = array();
				foreach($v->config as $opt_k => $opt_v)
				{
					$tmp[$opt_k] = $opt_v;
				}
				$v->config = $tmp;
			}

			if ( file_exists(dirname(__FILE__).'/../PFBC/Element/'.ucfirst($v->type).'.php') )
			{
				if($v->type === 'HTML')
				{
					// Custom HTML Data
					$this->parseHTMLObject($form, $v);
				}
				else if($v->type === 'Button')
				{
					if($v->name === 'Submit')
					{
						$form->addElement(new PFBC\Element\Button());
					}
					else
					{
						// Using Form Builder to build the input
						$field_options = array();
						if( ! is_null($v->options))
						{
							if(is_array($v->options))
							{
								$field_options = array_merge($field_options, $v->options);
							}
							else
							{
								foreach($v->options as $opt_k => $opt_v)
								{
									$field_options['$opt_k'] = $opt_v;
								}
							}
						}
						$form->addElement(new PFBC\Element\Button($v->name, 'button', $field_options));
					}
				}
				else if($v->type === 'Select')
				{
					// Using Form Builder to build the input
					$field_options = array();
					if( ! is_null($v->config))
					{
						$field_options = array_merge($field_options, $v->config);
					}

					$form->addElement(new PFBC\Element\Select($v->description.$this->options['label_seperator'], $v->name, $field_options));
				}
				else if($v->type === 'CheckboxOnly')
				{
					// Using Form Builder to build the input
					$field_options = array();
					if( ! is_null($v->config))
					{
						$field_options = array_merge($field_options, $v->config);
					}

					$form->addElement(new PFBC\Element\CheckboxOnly($v->description.$this->options['label_seperator'], $v->name, $field_options));
				}
				else
				{
					$type = "PFBC\\Element\\{$v->type}";
					// Using Form Builder to build the input
					$field_options = array();
					if( ! is_null($v->options))
					{
						if(is_array($v->options))
						{
							$field_options = array_merge($field_options, $v->options);
						}
						else
						{
							foreach($v->options as $opt_k => $opt_v)
							{
								$field_options['$opt_k'] = $opt_v;
							}
						}
					}

					$form->addElement(new $type($v->description.$this->options['label_seperator'], $v->name, $field_options));
				}
			}
			else
			{
				$this->parseHTMLObject($form, $v);
			}
		}

		foreach($this->open_tags as $k => $v)
		{
			//var_dump($k);
			//var_dump($v);
			switch(ucfirst($k))
			{
				case 'Row':
				case 'Col':
					$tag = 'div';
					break;

				default:
					$tag = strtolower($k);
			}

			if(is_array($v))
			{
				$v = $v[0];
			}
			if($v)
			{
				if($this->options['fieldset_div'] && $tag === 'fieldset')
				{
					$form->addElement(new Element\HTML('</div></fieldset>'));
				}
				else
				{
					$form->addElement(new Element\HTML('</'.$tag.'>'));
				}
			}
		}

		return '<'.$this->options['title_tag'].' class="'.$this->options['title_class'].'">'.$data->title.'</'.$this->options['title_tag'].'>' . $form->render(TRUE, $params);
	}


	/**
	 * Parse html tag
	 */
	protected function parseHTMLObject(&$form_obj, $field)
	{
		// Since this is not in the form builder will need to add as normal HTML
		switch( ucfirst($field->type) )
		{
			// CLEAR: will clear all the closing div to the right state before proceed
			// Will closed COL, ROW
			case 'Clear':
				$html = '';

				if(isset($this->open_tags['Col']) && $this->open_tags['Col'])
				{
					$html .= '</div>';
					$this->open_tags['Col'] = FALSE;
				}
				if($this->open_tags['Row'])
				{
					$html .= '</div>';
					$this->open_tags['Row'] = FALSE;
				}
				$html .= $field->description;
				break;

			case 'Row':

				$data_bind = ( ! empty($field->options['data-bind'])) ? ' data-bind="'.$field->options['data-bind'].'"' : '';

				$style = ($field->style) ? ' style="'.$field->style.'"': '';
				if($this->open_tags['Row'])
				{
					$html = '';

					// Check to see if the Col Still Open
					//var_dump($this->open_tags['Col']);
					if(isset($this->open_tags['Col']) && $this->open_tags['Col'])
					{
						$html .= '</div>';
						$this->open_tags['Col'] = FALSE;
					}

					// Close the div for Row
					$html .= '</div><div class="'.$field->class.'"'.$data_bind.$style.'>';
					$this->open_tags['Row'] = FALSE;
				}
				else
				{
					// Open div
					$html = '<div class="'.$field->class.'"'.$data_bind.$style.'>';
				}

				$this->open_tags['Row'] = ! $this->open_tags['Row'];
				break;

			case 'Col':

				$data_bind = ( ! empty($field->options['data-bind'])) ? ' data-bind="'.$field->options['data-bind'].'"' : '';

				$style = ($field->style) ? ' style="'.$field->style.'"': '';
				if($this->open_tags['Col'])
				{
					// Close the div for Col
					$html = '</div><div class="'.$field->class.'"'.$data_bind.$style.'>';
					$this->open_tags['Col'] = FALSE;
				}
				else
				{
					// Open div
					$html = '<div class="'.$field->class.'"'.$data_bind.$style.'>';
				}

				$this->open_tags['Col'] = ! $this->open_tags['Col'];
				break;

			case 'Fieldset':
				$html = '';

				if($this->open_tags['Row'])
				{
					// Check to see if the Col Still Open
					//var_dump($this->open_tags['Col']);
					if(isset($this->open_tags['Col']) && $this->open_tags['Col'])
					{
						$html .= '</div>';
						$this->open_tags['Col'] = FALSE;
					}

					// Close the div for Row
					$html .= '</div>';
					$this->open_tags['Row'] = FALSE;
				}

				if($this->open_tags['Fieldset'])
				{
					// Close the fieldset
					if($this->options['fieldset_div'])
					{
						$html .= '</div>';
					}

					$html .= '</fieldset>';
					$this->open_tags['Fieldset'] = FALSE;
				}

				// Open fieldset
				$opt = '';
				$div_class = '';
				if( count($field->options) )
				{
					foreach($field->options as $attr => $option)
					{
						if($attr === 'div_class')
						{
							$div_class = ' class="'.$option.'"';
						}
						$opt .= ' '.$attr.'="'.$option.'"';
					}
				}

				$html .= '<fieldset'.$opt.'>';

				if($this->options['fieldset_div'])
				{
					$html .= '<div'.$div_class.'>';
				}

				if($field->description)
				{
					$html .= '<'.$this->options['fieldset_tag'].'>' . $field->description . '</'.$this->options['fieldset_tag'].'>';
				}

				$this->open_tags['Fieldset'] = ! $this->open_tags['Fieldset'];
				break;

			case 'HTML':
				$html = $field->description;
				break;

			default:
				$html = '';
		}

		//var_dump($html);
		$form_obj->addElement(new Element\HTML($html));
	}
}