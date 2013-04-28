<?php
use PFBC\Form;
use PFBC\Element;

session_start();
error_reporting(E_ALL);
include("../PFBC/Form.php");

if(isset($_POST["form"])) {
	Form::isValid($_POST["form"]);
	header("Location: " . $_SERVER["PHP_SELF"]);
	exit();
}

include("../header.php");
$version = file_get_contents("../version");
?>
<div class="page-header">
	<h1>Form Builder from JSON data</h1>
</div>