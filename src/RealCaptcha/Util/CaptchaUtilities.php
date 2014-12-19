<?php
/*!
 * This file is a part of RealCaptcha human verification system.
 * Copyright (c) Ben New, Leftclick.com.au
 * See the LICENSE and README files in the main source directory for details.
 *
 * Partly based on "CaptchaSecurityImages.php" by Simon Jarvis, copyright 2006, updated: 07/02/07
 * http://www.white-hat-web-design.co.uk/articles/php-captcha.php
 */

namespace RealCaptcha\Util;

/**
 * Static utility classes for creating and manipulating colours within an image.
 *
 * @package RealCaptcha\Util
 */
class CaptchaUtilities {

	private static $randomFunction = null;

	/**
	 * Generate a random integer in the given range, using the best available function.
	 *
	 * @param int $min Lowest value to return.
	 * @param int $max Highest value to return.
	 *
	 * @return int Value in range [$min..$max] (inclusive).
	 */
	public static function random($min, $max) {
		if (is_null(self::$randomFunction)) {
			self::$randomFunction = function_exists('mt_rand') ? 'mt_rand' : 'rand';
		}
		$function = self::$randomFunction;
		return $function($min, $max);
	}

	/**
	 * Accept either an integer, or an array with 'min' and 'max' keys, each mapped to an integer.  Return the integer
	 * unchanged or an integer in the range defined by an array.  Any other input is an error.
	 *
	 * @param integer|array $value
	 *
	 * @return int
	 */
	public static function getValueFromRange($value) {
		if (is_array($value)) {
			if (!isset($value['min']) || !isset($value['max'])) {
				throw new \RuntimeException('Cannot getValueFromRange() without both a "min" and a "max" input');
			}
			if (!is_int($value['min']) || !is_int($value['max'])) {
				throw new \RuntimeException('Cannot getValueFromRange() with non-numeric "min" or "max" input');
			}
			$value = CaptchaUtilities::random($value['min'], $value['max']);
		}
		if (!is_int($value)) {
			throw new \RuntimeException('Cannot accept non-array, non-numeric argument in getValueFromRange()');
		}
		return $value;
	}

}
