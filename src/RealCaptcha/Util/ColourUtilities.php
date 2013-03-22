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
class ColourUtilities {

	/**
	 * Create a colour reference for the given image, using the given colour which is an array containing 'red',
	 * 'green' and 'blue' keys, each of which has an integer value in the range 0-255.
	 *
	 * @param resource $image
	 * @param array $colour
	 *
	 * @return integer
	 */
	public static function createColour($image, array $colour) {
		return imagecolorallocate($image, self::getColourComponent($colour['red']), self::getColourComponent($colour['green']), self::getColourComponent($colour['blue']));
	}

	/**
	 * Get a single colour component (red, green or blue), including conversion from an array containing 'min' and
	 * 'max' keys, to a single random value.
	 *
	 * @param integer|array $value
	 *
	 * @return int
	 */
	public static function getColourComponent($value) {
		if (is_array($value)) {
			$value = mt_rand($value['min'], $value['max']);
		}
		return $value;
	}

}
