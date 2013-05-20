<?php
use FBUILDER\FormBuilder;
use FBUILDER\FormObject;
use FBUILDER\FormField;

session_start();
ini_set('display_errors', 1);
error_reporting(E_ALL);
include("../FBUILDER/FormBuilder.php");

if(isset($_SESSION["form_token"]) && isset($_POST[$_SESSION["form_token"]])) {
	var_dump($_POST);
	exit();
}

include("../header.php");
?>
<div class="page-header">
	<h1>Form Builder from JSON data</h1>
</div>

<?php

$json = '
{
	"form_setup" : {
		"view" : "FormWizard",
		"data-model-name" : "FormViewModel"
	},
	"form_options" : {
		"title_class" : "text-info",
		"fieldset_div" : "TRUE"
	},
	"view_options" : {
		"slides-name" : [
			"USER",
			"SECOND"
		],
		"slides-ico-class" : [
			"icon-user icon-3x",
			"icon-envelope icon-3x"
		],
		"css-properties": {
			"form-height" : "280px"
		}
	},
	"form_object" : {
		"name" : "php_form_builder",
		"title" : "Example Form Wizard (Bootstrap)",
		"fields" : [
		{
			"type" : "Fieldset",
			"description" : "User Information"
		},

		{
			"type" : "Row",
			"class" : "row-fluid"
		},
		{
			"type" : "Col",
			"class" : "span12"
		},
		{
			"name" : "full_name",
			"type" : "FullName",
			"description" : "Name"
		},

		{
			"type" : "Row",
			"class" : "row-fluid"
		},
		{
			"type" : "Col",
			"class" : "span12"
		},
		{
			"name" : "street_address",
			"type" : "Textbox",
			"description" : "Street Address"
		},

		{
			"type" : "Row",
			"class" : "row-fluid"
		},
		{
			"type" : "Col",
			"class" : "span6"
		},
		{
			"name" : "city",
			"type" : "Textbox",
			"description" : "City"
		},
		{
			"type" : "Col",
			"class" : "span6"
		},
		{
			"name" : "state",
			"type" : "State",
			"description" : "State"
		},

		{
			"type" : "Row",
			"class" : "row-fluid"
		},
		{
			"type" : "Col",
			"class" : "span6"
		},
		{
			"name" : "country",
			"type" : "Textbox",
			"description" : "Country"
		},
		{
			"type" : "Col",
			"class" : "span6"
		},
		{
			"name" : "phone",
			"type" : "Phone",
			"description" : "Phone"
		},

		{
			"type" : "Fieldset",
			"description" : "Second Step"
		},

		{
			"type" : "Row",
			"class" : "row-fluid"
		},
		{
			"type" : "Col",
			"class" : "span6"
		},
		{
			"name" : "dob",
			"type" : "Date",
			"description" : "Date of Birth"
		},
		{
			"type" : "Col",
			"class" : "span6"
		},
		{
			"name" : "gender",
			"type" : "Gender",
			"description" : "Gender"
		},
		{
			"type" : "Row",
			"class" : "row-fluid"
		},
		{
			"type" : "Col",
			"class" : "span12"
		},
		{
			"name" : "test_radio",
			"type" : "Radio",
			"description" : "Radio",
			"config" : {
				"options": ["Yes", "No"],
				"required" : "1"
			}
		}
		]
	}
}
';

$decoded_json = json_decode($json);
//echo var_dump($decoded_json);

if(empty($decoded_json))
{
	throw new Exception('Invalid JSON data.');
}

	$formbuilder = new FormBuilder($decoded_json->form_setup, $decoded_json->form_options);

	$form_object = new FormObject($decoded_json->form_object);
	$form = $formbuilder->parseFormSchema($form_object, $decoded_json->view_options);

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
