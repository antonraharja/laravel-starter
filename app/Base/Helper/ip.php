<?php

if (!function_exists('matchIP')) {
	/**
	 * Function: matchIP()
	 * ref: https://github.com/mlocati/ip-lib
	 * ref: https://github.com/playsms/playsms/blob/master/storage/application/lib/fn_core.php#L668
	 *
	 * This function returns a boolean value.
	 * Usage: core_net_match("IP RANGE", "IP ADDRESS")
	 * 
	 * @param string $network Network
	 * @param string $ip IP to be checked within network
	 * @return bool
	 */
	function matchIP($network, $ip): bool
	{
		$network = trim($network);
		$ip = trim($ip);

		if ($network && $ip && class_exists('\IPLib\Factory')) {

			// don't match with network that starts with asterisk or 0
			// to prevent matches with *.*.*.* or 0.0.0.0
			if (preg_match('/^[\*0]/', $network)) {

				return false;
			}

			try {
				$address = \IPLib\Factory::parseAddressString($ip);
				$range = \IPLib\Factory::parseRangeString($network);

				if (is_object($address) && is_object($range) && $address->matches($range)) {

					return true;
				} else {

					return false;
				}
			} catch (Exception $e) {

				return false;
			}
		} else {

			return false;
		}
	}
}

if (!function_exists('isIP')) {
	/**
	 * Check if given IP address/network is valid
	 * 
	 * @param string $ip IP address/network
	 * @return bool
	 */
	function isIP(string $ip): bool
	{
		$address = \IPLib\Factory::parseAddressString($ip);
		if (is_object($address)) {

			return true;
		}

		$range = \IPLib\Factory::parseRangeString($ip);
		if (is_object($range)) {

			return true;
		}

		return false;
	}
}