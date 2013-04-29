<?php
namespace PFBC\View;

class FormWizard extends \PFBC\View {
	protected $class = "form-horizontal";
	public $js = "rhinoslider";

	function __construct()
	{
		parent::__construct();

		// Adding Default Parameter
		$this->params = array(
			'fieldset' => FALSE,	// Auto Include fieldset after the form
			'form-actions' => FALSE,	// Auto wrap button with form-actions div
			'slides-name' => array(),
			'slides-ico-class' => array(),
			'css-properties' => array(	// Set up default form width and height
				'form-height' => '500px',
				'form-width' => '750px',
			)
		);
	}

	public function render() {

		$this->_form->appendAttribute("class", $this->class);

		if( $this->params['fieldset'] )
		{
			echo '<form', $this->_form->getAttributes(), '><fieldset>';
		}
		else
		{
			echo '<form', $this->_form->getAttributes(), '>';
		}

		// Declare the Rhino Form
		echo '<div class="slider">';

		$this->_form->getErrorView()->render();

		$elements = $this->_form->getElements();
		$elementSize = sizeof($elements);
		$elementCount = 0;
		for($e = 0; $e < $elementSize; ++$e) {
			$element = $elements[$e];

			if($element instanceof \PFBC\Element\Hidden || $element instanceof \PFBC\Element\HTML)
				$element->render();
            elseif($element instanceof \PFBC\Element\Button) {

				if($this->params['form-actions'])
				{
					if($e == 0 || !$elements[($e - 1)] instanceof \PFBC\Element\Button)
						echo '<div class="form-actions">';
					else
						echo ' ';
				}

				$element->render();

				if($this->params['form-actions'])
				{
					if(($e + 1) == $elementSize || !$elements[($e + 1)] instanceof \PFBC\Element\Button)
						echo '</div>';
				}
            }
			elseif($element instanceof \PFBC\Element\CheckboxOnly) {
				echo '<div class="control-group options-only" id="element_', $element->getAttribute('id'), '">', $element->render(), $this->renderDescriptions($element), '</div>';
				++$elementCount;
			}
            else {
				$class = $element->getAttribute("container_class");	// Adding Class to the container div
				$data_bind = $element->getAttribute("data-bind");	// Adding data-bind to the container div
				if( ! empty($class))
				{
					$class = ' ' . $class;
				}
				if( ! empty($data_bind))
				{
					$data_bind = ' ' . $data_bind;
				}

				echo '<div class="control-group'.$class.'" id="element_', $element->getAttribute('id'), '"'.$data_bind.'>', $this->renderLabel($element), '<div class="controls">', $element->render(), $this->renderDescriptions($element), '</div></div>';
				++$elementCount;
			}
		}

		// Close the Rhino Form Div
		echo '</div>';

		if( $this->params['fieldset'] )
		{
			echo '</fieldset></form>';
		}
		else
		{
			echo '</form>';
		}

    }

	protected function renderLabel(\PFBC\Element $element) {
        $label = $element->getLabel();
        if(!empty($label)) {
			echo '<label class="control-label" for="', $element->getAttribute("id"), '">';
			if($element->isRequired())
				echo '<span class="required">* </span>';
			echo $label, '</label>';
        }
    }

	// Set Up Init Variables
	public function renderJS()
	{
		$html = 'var form_wizard = {';
		$object_array = array();
		if( isset($this->params['slides-name'][0]))
		{
			$html_tmp = 'name:[';
			for($i =0, $j = count($this->params['slides-name']); $i < $j; $i++)
			{
				$html_tmp .= '"'.$this->params['slides-name'][$i].'"';
				if($i < ($j-1))
				{
					$html_tmp .= ',';
				}
			}
			$html_tmp .= ']';

			array_push($object_array, $html_tmp);
		}

		if( isset($this->params['slides-ico-class'][0]))
		{
			$html_tmp = 'ico_class:[';
			for($i =0, $j = count($this->params['slides-ico-class']); $i < $j; $i++)
			{
				$html_tmp .= '"'.$this->params['slides-ico-class'][$i].'"';
				if($i < ($j-1))
				{
					$html_tmp .= ',';
				}
			}
			$html_tmp .= ']';

			array_push($object_array, $html_tmp);
		}

		$html .= implode(',', $object_array);
		$html .= "};\n";

		echo $html;
	}


	// Set Up the Rhino Slider Wizard
	public function jQueryDocumentReady()
	{
		// Setup the FormWizard
		echo <<< JS
$('.slider').rhinoslider({
	controlsMousewheel: false,
	controlsPlayPause: false,
	controlsKeyboard: false,
	cycled: false,
	showBullets: 'always',
	showControls: 'always',
	slidePrevDirection: 'toRight',
	slideNextDirection: 'toLeft',
	prevText: 'Back',
	nextText: 'Next',
	bulletsClick: false
});

// Setup the Steps
$('.rhino-bullet').each(function(index){
	var link_text = $(this).html();
	var description = $("#rhino-item"+(link_text-1)).attr("data");
	$(this).html('<p><i class="'+form_wizard.ico_class[index]+'"></i></p><p class="title">'+form_wizard.name[index]+'</p></a>');
});

JS;
	}

	// Render CSS
	public function renderCSS()
	{
		parent::renderCSS();

		$bullets_top =  (- 10) - (int)$this->params['css-properties']['form-height'];
		$bullets_top .= 'px';

		$total_name = count($this->params['slides-name']);
		if( $total_name > 0)
		{
			$bullet_width = (int)$this->params['css-properties']['form-width'] / (float)$total_name;
			$bullet_width -= 2;	// Subtract margin
			$bullet_width .= 'px';
		}
		else
		{
			$bullet_width = '100px';
		}

		echo "\n".
".slider {
	height: {$this->params['css-properties']['form-height']};
	width: {$this->params['css-properties']['form-width']}
}
.rhino-bullets {
	top: $bullets_top
}
.rhino-bullets li {
	width: $bullet_width
}
";
	}

}
