<?php
namespace PFBC\View;

class FuelWizard extends \PFBC\View {
	protected $class = "form-horizontal";
	public $js = "fuelwizard";

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
		$this->renderWizard();

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

	public function renderWizard()
	{
		echo '<div class="fuelux">';
		echo '<div id="'.$this->_form->getAttribute('id').'_wizard" class="wizard">
	<ul class="steps">';

		foreach($this->params['slides-name'] as $k => $v)
		{
			$k++;

			$first_tab = ($k === 1) ? 'class="active" ': '';

			echo '<li '.$first_tab.'data-target="#step'.$k.'"><span class="badge badge-info">'.$k.'</span>'. $v .'<span class="chevron"></span></li>';
		}

		echo '</ul>
	<div class="actions">
		<button type="button" class="btn btn-primary btn-prev" disabled=""> <i class="icon-arrow-left"></i>Prev</button>
		<button type="button" class="btn btn-primary btn-next" data-last="Finish">Next<i class="icon-arrow-right"></i></button>
	</div>
</div>';

		$this->_form->appendAttribute("class", $this->class);

		echo '<div class="step-content">';

		echo '<form', $this->_form->getAttributes(), '>';

		// Form Name
		echo '<input type="hidden" name="FormName" value="'.$this->_form->getAttribute('id').'"/>';
		// Generate CSRF
		echo '<input type="hidden" name="'.$_SESSION["form_token"].'" value="1"/>';

		$elements = $this->_form->getElements();
		$elementSize = sizeof($elements);
		$elementCount = 0;
		$step = 1;
		for($e = 0; $e < $elementSize; ++$e) {
			$element = $elements[$e];
			// Set the data-validation-name to be used as form validation
			$data_validation_name = $element->getAttribute('data-validation-name');
			if( empty($data_validation_name))
			{
				$element->setAttribute('data-validation-name', preg_replace('/:/','',$element->getLabel()));
			}

			if($element instanceof \PFBC\Element\Hidden || $element instanceof \PFBC\Element\HTML)
			{
				// Render Each Step;
				//print_r(htmlentities($element->getAttribute('value')));
				// If found fieldset mean this is the new div

				$last_fieldset = stripos($element->getAttribute('value'), '</fieldset>');

				if( stripos($element->getAttribute('value'), '<fieldset ') !== FALSE )
				{
					//echo 'Start' . '<br>';
					//echo htmlentities($element->getAttribute('value')) . '<br>';
					$first_tab = ($step === 1) ? ' active': '';
					echo '<div class="step-pane'.$first_tab.'" id="step'.$step.'">';

					$element->render();
				}
				else if($last_fieldset !== FALSE)
				{
					$step++;
					//echo 'Debug' . '<br>';
					//echo htmlentities($element->getAttribute('value')) . '<br>';
					echo substr($element->getAttribute('value'), 0, $last_fieldset);
					echo '</fieldset>';
					//echo '<h1>'.$step.'</h1><h2>'.count($this->params['slides-name']).'</h2>';

					if( count($this->params['slides-name']) >= $step)
					{
						echo '</div><div class="step-pane" id="step'.$step.'">';
						echo substr($element->getAttribute('value'), $last_fieldset+11);
					}
					else {
						//echo htmlentities($element->getAttribute('value')) . '<br>';
						echo '</div>';
					}
					//echo 'Done' . '<br>';
				}
				else
				{
					$element->render();
				}
			}
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

		echo '</form>';
		echo '</div></div>';
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

	}

	// Set Up the Rhino Slider Wizard
	public function jQueryDocumentReady()
	{
		echo ' jQuery("#'.$this->_form->getAttribute('id').'_wizard").wizard();';
	}

	public function renderCSS()
	{

	}
}
