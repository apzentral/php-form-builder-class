<?php
use FBUILDER\FormBuilder;
use FBUILDER\FormObject;
use FBUILDER\FormField;

session_start();
ini_set('display_errors', 1);
error_reporting(E_ALL);
include("../FBUILDER/FormBuilder.php");

include("../header.php");
?>
<div class="page-header">
	<h1>Form Builder from JSON data</h1>
</div>

<?php

$vars = array(
		'title' => 'Form Wizard with Bootstrap',
		'js' => array()
	);

	// Create a Form Here
	$form_object = new FormObject( array(
		'name' => 'php_form_builder',
		'title' => 'Example Form Wizard'
	) );

	$options_array = array(
		'option 1', 'option 2', 'option 3'
	);

	// Adding Fields
	$fields = array();

	// Start Building the Form

	//===== User Info Fieldset =====//
	$fields[] = new FormField( array(
		'type' => 'Fieldset',
		'description' => 'User Information'
	));

	//===== ROW ====//
	$fields[] = new FormField( array(
		'type' => 'Row',
		'class' => 'row-fluid'
	));

	$fields[] = new FormField( array(
		'type' => 'Col',
		'class' => 'span12'
	));

	$fields[] = new FormField( array(
		'name' => 'full_name',
		'type' => 'FullName',
		'description' => 'Name'
	));

	//===== ROW ====//
	$fields[] = new FormField( array(
		'type' => 'Row',
		'class' => 'row-fluid'
	));

	$fields[] = new FormField( array(
		'type' => 'Col',
		'class' => 'span12'
	));

	$fields[] = new FormField( array(
		'name' => 'street_address',
		'type' => 'Textbox',
		'description' => 'Street Address'
	));

	//===== ROW ====//
	$fields[] = new FormField( array(
		'type' => 'Row',
		'class' => 'row-fluid'
	));

	$fields[] = new FormField( array(
		'type' => 'Col',
		'class' => 'span6'
	));

	$fields[] = new FormField( array(
		'name' => 'city',
		'type' => 'Textbox',
		'description' => 'City'
	));

	$fields[] = new FormField( array(
		'type' => 'Col',
		'class' => 'span6'
	));

	$fields[] = new FormField( array(
		'name' => 'state',
		'type' => 'State',
		'description' => 'State'
	));

	//===== ROW ====//
	$fields[] = new FormField( array(
		'type' => 'Row',
		'class' => 'row-fluid'
	));

	$fields[] = new FormField( array(
		'type' => 'Col',
		'class' => 'span6'
	));

	$fields[] = new FormField( array(
		'name' => 'country',
		'type' => 'Textbox',
		'description' => 'Country'
	));

	$fields[] = new FormField( array(
		'type' => 'Col',
		'class' => 'span6'
	));

	$fields[] = new FormField( array(
		'name' => 'phone',
		'type' => 'Phone',
		'description' => 'Phone'
	));

	//===== Second Info Fieldset =====//
	$fields[] = new FormField( array(
		'type' => 'Fieldset',
		'description' => 'Second Step'
	));

	//===== ROW ====//
	$fields[] = new FormField( array(
		'type' => 'Row',
		'class' => 'row-fluid'
	));

	$fields[] = new FormField( array(
		'type' => 'Col',
		'class' => 'span6'
	));

	$fields[] = new FormField( array(
		'name' => 'dob',
		'type' => 'Date',
		'description' => 'Date of Birth'
	));

	$fields[] = new FormField( array(
		'type' => 'Col',
		'class' => 'span6'
	));

	$fields[] = new FormField( array(
		'name' => 'gender',
		'type' => 'Gender',
		'description' => 'Gender'
	));

	//===== Assign Fields to the form object =====//
	$form_object->fields = $fields;

	$form_builder_options = array(
		'view' => 'FormWizard',
		'data-model-name' => 'FormViewModel'
	);

	$form_builder_setup = array(
		'title_class' => 'text-info',
		'fieldset_div' => TRUE
	);

	$view_options = array(
		'slides-name' => array(
			"USER",
			"SECOND"
		),
		'slides-ico-class' => array(
			'icon-user icon-3x',
			'icon-envelope icon-3x'
		),
		'css-properties' => array(
			'form-height' => '500px'
		)
	);

	$formbuilder = new FormBuilder($form_builder_options, $form_builder_setup);
	$form = $formbuilder->parseFormSchema($form_object, $view_options);

?>

<div class="row-fluid">
	<div class="span12">
		<?php
		echo $form;
		?>
	</div>
</div>
<?php
include("../footer.php");
