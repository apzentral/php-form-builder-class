<?php
namespace FBUILDER;

abstract class FBuilderHelper {

	/**
	 * Function to set random Token and set up the timeout
	 */
	public static function randomTokenSession($name, $timeout = '')
	{
		if( ! isset($_SESSION))
		{
			session_start();
		}

		if($timeout !== '')
		{
			if(isset($_SESSION[$name.'_TIME']))
			{
				if( (time() - $_SESSION[$name.'_TIME']) > $_SESSION[$name.'_DURATION'])
				{
					// Unset Time and Session Name
					unset($_SESSION[$name.'_TIME']);
					unset($_SESSION[$name]);
				}
			}
			else
			{
				$_SESSION[$name.'_DURATION'] = $timeout;
				$_SESSION[$name.'_TIME'] = time();
			}
		}

		if( ! isset($_SESSION[$name]))
		{
			$_SESSION[$name] = md5(uniqid(mt_rand(), true));
		}
	}

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

	public static function isMobile()
	{
		$user_agent = $_SERVER['HTTP_USER_AGENT'];

		$mobile_agents = Array(
			"240x320",
			"acer",
			"acoon",
			"acs-",
			"abacho",
			"ahong",
			"airness",
			"alcatel",
			"amoi",
			"android",
			"anywhereyougo.com",
			"applewebkit/525",
			"applewebkit/532",
			"asus",
			"audio",
			"au-mic",
			"avantogo",
			"becker",
			"benq",
			"bilbo",
			"bird",
			"blackberry",
			"blazer",
			"bleu",
			"cdm-",
			"compal",
			"coolpad",
			"danger",
			"dbtel",
			"dopod",
			"elaine",
			"eric",
			"etouch",
			"fly " ,
			"fly_",
			"fly-",
			"go.web",
			"goodaccess",
			"gradiente",
			"grundig",
			"haier",
			"hedy",
			"hitachi",
			"htc",
			"huawei",
			"hutchison",
			"inno",
			"ipad",
			"ipaq",
			"ipod",
			"jbrowser",
			"kddi",
			"kgt",
			"kwc",
			"lenovo",
			"lg ",
			"lg2",
			"lg3",
			"lg4",
			"lg5",
			"lg7",
			"lg8",
			"lg9",
			"lg-",
			"lge-",
			"lge9",
			"longcos",
			"maemo",
			"mercator",
			"meridian",
			"micromax",
			"midp",
			"mini",
			"mitsu",
			"mmm",
			"mmp",
			"mobi",
			"mot-",
			"moto",
			"nec-",
			"netfront",
			"newgen",
			"nexian",
			"nf-browser",
			"nintendo",
			"nitro",
			"nokia",
			"nook",
			"novarra",
			"obigo",
			"palm",
			"panasonic",
			"pantech",
			"philips",
			"phone",
			"pg-",
			"playstation",
			"pocket",
			"pt-",
			"qc-",
			"qtek",
			"rover",
			"sagem",
			"sama",
			"samu",
			"sanyo",
			"samsung",
			"sch-",
			"scooter",
			"sec-",
			"sendo",
			"sgh-",
			"sharp",
			"siemens",
			"sie-",
			"softbank",
			"sony",
			"spice",
			"sprint",
			"spv",
			"symbian",
			"tablet",
			"talkabout",
			"tcl-",
			"teleca",
			"telit",
			"tianyu",
			"tim-",
			"toshiba",
			"tsm",
			"up.browser",
			"utec",
			"utstar",
			"verykool",
			"virgin",
			"vk-",
			"voda",
			"voxtel",
			"vx",
			"wap",
			"wellco",
			"wig browser",
			"wii",
			"windows ce",
			"wireless",
			"xda",
			"xde",
			"zte"
		);

		// Pre-set $is_mobile to false.

		$is_mobile = false;

		// Cycle through the list in $mobile_agents to see if any of them
		// appear in $user_agent.

		foreach ($mobile_agents as $device) {

			// Check each element in $mobile_agents to see if it appears in
			// $user_agent.  If it does, set $is_mobile to true.

			if (stristr($user_agent, $device)) {

				$is_mobile = true;

				// break out of the foreach, we don't need to test
				// any more once we get a true value.

				break;
			}
		}

		return $is_mobile;
	}
}
