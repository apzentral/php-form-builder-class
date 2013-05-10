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

		echo '<div class="form-modal-wrapper">';

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

		echo '</div>';

		// Modal Body
		echo '<div class="modal-body form-horizontal">';

		echo '</div>';

		// Modal Footer
		echo '<div class="modal-footer">';
		echo '<button class="btn" data-dismiss="modal" aria-hidden="true" id="'.$this->_form->getAttribute('id').'_cancel">'.$this->params['actions']['cancel-val'].'</button>';
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

}