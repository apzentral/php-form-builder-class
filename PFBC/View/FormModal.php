<?php
namespace PFBC\View;

class FormModal extends \PFBC\View {
	protected $class = "form-horizontal";

	function __construct()
	{
		parent::__construct();

		// Adding Default Parameter
		$this->params = array(
			'sub-form' => TRUE,	// If we will use this as sub-form
			'fieldset' => FALSE,	// Auto Include fieldset after the form
			'form-actions' => FALSE,	// Auto wrap button with form-actions div
			'css-properties' => array(	// Set up default form width and height
				'form-width' => 'auto',
				'form-height' => 'auto',
			),
			'container-attr' => array(
				'class' => '',
				'data-bind' => '',
				'id' => '',
				'style' => '',
				'other' => ''
			),
			'actions' => array(
				'data-bind-submit' => '',
				'submit-val' => 'Submit',
				'cancel-val' => 'Cancel'
			),
			'after-form-render' => array()	// If user want to add custom html after form
		);
	}

	public function render() {

		$this->_form->appendAttribute("class", $this->class);

		$container_class = ($this->params['container-attr']['class'] === '') ? '': ' '.$this->params['container-attr']['class'];
		$container_attr = ($this->params['container-attr']['data-bind'] === '') ? '': ' data-bind="'.$this->params['container-attr']['data-bind'].'"';
		$container_attr .= ($this->params['container-attr']['id'] === '') ? '': ' id="'.$this->params['container-attr']['id'].'"';
		$container_attr .= ($this->params['container-attr']['style'] === '') ? '': ' style="'.$this->params['container-attr']['style'].'"';
		$container_attr .= ($this->params['container-attr']['other'] === '') ? '': ' '.$this->params['container-attr']['other'];
		echo '<div class="form-modal-wrapper'.$container_class.'"'.$container_attr.'>';

		if( $this->params['fieldset'] )
		{
			echo '<form', $this->_form->getAttributes(), '><fieldset>';
		}
		else
		{
			echo '<form', $this->_form->getAttributes(), '>';
		}

		if( $this->params['sub-form'] )
		{
			// Form Name
			echo '<input type="hidden" name="FormName" value="'.$this->_form->getAttribute('id').'"/>';
			// Generate CSRF
			echo '<input type="hidden" name="'.$_SESSION["form_token"].'" value="1"/>';
		}

		// Modal Header
		echo '<div class="modal-header">';
		echo '<button class="close" aria-hidden="true" data-dismiss="modal" type="button">X</button>';
		echo $this->_form->subFormTitle;
		echo '</div>';

		// Modal Body
		echo '<div class="modal-body form-horizontal">';
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
		echo '</div>';

		// Modal Footer
		echo '<div class="modal-footer text-right">';
		echo '<button class="btn" data-dismiss="modal" aria-hidden="true" id="'.$this->_form->getAttribute('id').'_cancel" style="margin-right:40px;">'.$this->params['actions']['cancel-val'].'</button>';
		echo '<button type="submit" class="btn btn-primary" id="'.$this->_form->getAttribute('id').'_submit">'.$this->params['actions']['submit-val'].'</button>';
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


		if( ! $this->params['sub-form'] )
		{
			// Respond Output
			echo '<div id="respond-output"></div>';
		}

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

		// Render CSS
	public function renderCSS()
	{
		parent::renderCSS();

echo "\n".
"
#".$this->_form->getAttribute('id')." {
	height : ".$this->params['css-properties']['form-height'].";
	width : ".$this->params['css-properties']['form-width'].";
}
.form-modal-wrapper .modal-header {
	background-color: #F5F5F5;
	padding: 15px 30px;
}
.form-modal-wrapper .modal-header h3{
	border-bottom: none;
	margin: 0;
}
.form-modal-wrapper .modal-footer {
	padding-top: 20px;
	padding-bottom: 0px;
}
";
	}
}