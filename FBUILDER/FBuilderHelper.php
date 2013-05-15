<?php
namespace FBUILDER;

abstract class FBuilderHelper {

	/**
	 * Check Browser Version
	 * isBrowser('ie', 8)
	 * isBrowser('ie', '[1-8]')
	 */
	public static function isBrowser($browser_name, $browser_version)
	{
		switch($browser_name)
		{
			case 'ie':
				$browser_version = (is_string($browser_version)) ? $browser_version: '7-'.$browser_version;
				$browser_name = '/msie ['.$browser_version.']/i';
				break;

			case 'firefox':
				$browser_name = '/firefox/i';
				break;

			case 'chrome':
				$browser_name = '/chrome/i';
				break;

			case 'safari':
				$browser_name = '/safari/i';
				break;

			case 'flock':
				$browser_name = '/flock/i';
				break;

			case 'opera':
				$browser_name = '/opera/i';
				break;
		}

		return preg_match($browser_name, $_SERVER['HTTP_USER_AGENT']);
	}
}
