<?php
namespace PFBC\Element;

class Race extends Select {
	public function __construct($label, $name, array $properties = null) {
		$options = array(
			"" => "--Select Race--",
			"White" => "White",
			"Black" => "Black",
			"Asian" => "Asian",
			"American Indian/Alaska Native" => "American Indian/Alaska Native",
			"Other" => "Other"
		);
		parent::__construct($label, $name, $options, $properties);
    }
}
