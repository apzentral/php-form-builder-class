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
			),
			'after-form-render' => array()	// If user want to add custom html after form
		);
	}

	public function render()
	{
		if($this->_form->ie7)
		{
			$this->renderIE7();
		}
		else
		{
			$this->renderNormal();
		}

	}

	public function renderIE7()
	{
		echo '<div id="'.$this->_form->getAttribute('id').'_wizard" class="wizard">
	<ul class="steps">
		<li data-target="#step1" class="active"><span class="badge badge-info">1</span>Step 1<span class="chevron"></span></li>
		<li data-target="#step2"><span class="badge">2</span>Step 2<span class="chevron"></span></li>
		<li data-target="#step3"><span class="badge">3</span>Step 3<span class="chevron"></span></li>
		<li data-target="#step4"><span class="badge">4</span>Step 4<span class="chevron"></span></li>
		<li data-target="#step5"><span class="badge">5</span>Step 5<span class="chevron"></span></li>
	</ul>
	<div class="actions">
		<button type="button" class="btn btn-mini btn-prev"> <i class="icon-arrow-left"></i>Prev</button>
		<button type="button" class="btn btn-mini btn-next" data-last="Finish">Next<i class="icon-arrow-right"></i></button>
	</div>
</div>';

		$this->_form->appendAttribute("class", $this->class);

		echo '<div class="step-content">';

		echo '<form', $this->_form->getAttributes(), '>';

		// Form Name
		echo '<input type="hidden" name="FormName" value="'.$this->_form->getAttribute('id').'"/>';
		// Generate CSRF
		echo '<input type="hidden" name="'.$_SESSION["form_token"].'" value="1"/>';


		// Render Each Step;
		echo '<div class="step-pane active" id="step1">This is step 1</div>
<div class="step-pane" id="step2">This is step 2</div>
<div class="step-pane" id="step3">This is step 3</div>
<div class="step-pane" id="step4">This is step 4</div>
<div class="step-pane" id="step5">This is step 5</div>';


		echo '</form>';

		echo '</div>';
	}

	public function renderNormal()
	{

		$this->_form->appendAttribute("class", $this->class);

		echo '<div class="rhino-form-wrapper">';

		if( $this->params['fieldset'] )
		{
			echo '<form', $this->_form->getAttributes(), '><fieldset>';
		}
		else
		{
			echo '<form', $this->_form->getAttributes(), '>';
		}

		// Form Name
		echo '<input type="hidden" name="FormName" value="'.$this->_form->getAttribute('id').'"/>';
		// Generate CSRF
		echo '<input type="hidden" name="'.$_SESSION["form_token"].'" value="1"/>';

		// Declare the Rhino Form
		echo '<div class="slider">';

		$this->_form->getErrorView()->render();

		$elements = $this->_form->getElements();
		$elementSize = sizeof($elements);
		$elementCount = 0;
		for($e = 0; $e < $elementSize; ++$e) {
			$element = $elements[$e];
			// Set the data-validation-name to be used as form validation
			$data_validation_name = $element->getAttribute('data-validation-name');
			if( empty($data_validation_name))
			{
				$element->setAttribute('data-validation-name', preg_replace('/:/','',$element->getLabel()));
			}

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
				if( ! empty($class))
				{
					$class = ' ' . $class;
				}

				$data_bind = ($element->getAttribute('data-bind-div') != '') ? ' data-bind="'.$this->filter($element->getAttribute('data-bind-div')).'"': '';
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

		echo '</div>';

		// Modal to show fields error
		echo '
<div id="form-modal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-header alert alert-error">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">X</button>
		<h3 id="modal-title"></h3>
	</div>
	<div class="modal-body">
		<div id="modal-body-text"></div>
	</div>
	<div class="modal-footer">
		<button class="btn btn btn-primary" data-dismiss="modal" aria-hidden="true">Close</button>
	</div>
</div>';

		// Respond Output
		echo '<div id="respond-output"></div>';

		// If this form has after html render
		$this->params['after-form-render'] = (array)$this->params['after-form-render'];	// Cast to array
		if(isset($this->params['after-form-render'][0]))
		{
			foreach($this->params['after-form-render'] as $data)
			{
				echo $data;
			}
		}
    }

	protected function renderLabel(\PFBC\Element $element) {
        $label = $element->getLabel();
        if(!empty($label)) {
			$data_bind = ($element->getAttribute('data-bind-label') != '') ? ' data-bind="'.$this->filter($element->getAttribute('data-bind-label')).'"': '';
			echo '<label class="control-label" for="', $element->getAttribute("id"), '"'.$data_bind.'>';
			if($element->isRequired())
				echo '<span class="required">* </span>';
			echo $label, '</label>';
        }
    }

	public function renderJS()
	{
		if($this->_form->ie7)
		{
			$this->renderJSIE7();
		}
		else
		{
			$this->renderJSNormal();
		}
	}

	public function renderJSIE7()
	{
	}

	// Set Up Init Variables
	public function renderJSNormal()
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
		if($this->_form->ie7)
		{
			echo 'jQuery("#'.$this->_form->getAttribute('id').'_wizard").wizard();';
		}
		else
		{
			// Setup the FormWizard
		echo <<< JS
// Setup the Steps
jQuery('.rhino-bullet').each(function(index){
	var link_text = jQuery(this).html();
	var description = jQuery("#rhino-item"+(link_text-1)).attr("data");
	jQuery(this).html('<p><i class="'+form_wizard.ico_class[index]+'"></i></p><p class="title">'+form_wizard.name[index]+'</p></a>');
});

JS;
		}
	}

	public function renderCSS()
	{
		if($this->_form->ie7)
		{
			$this->renderCSSIE7();
		}
		else
		{
			$this->renderCSSNormal();
		}
	}

	public function renderCSSIE7()
	{

	}

	// Render CSS
	public function renderCSSNormal()
	{
		parent::renderCSS();

		$form_height =  (int)$this->params['css-properties']['form-height'] + 200;
		$form_height .= 'px';

		$total_name = count($this->params['slides-name']);
		if( $total_name > 0)
		{
			$bullet_width = (int)$this->params['css-properties']['form-width'] / (float)$total_name;
			$bullet_width -= 1;	// Subtract margin
			$bullet_width .= 'px';
		}
		else
		{
			$bullet_width = '100px';
		}

		echo "\n".
"
.rhino-form-wrapper {
	height: $form_height;
	overflow: hidden;
	clear: both;
}
.slider {
	height: {$this->params['css-properties']['form-height']};
	width: {$this->params['css-properties']['form-width']};
}
.rhino-bullets li {
	width: $bullet_width;
}
";
	}

}
