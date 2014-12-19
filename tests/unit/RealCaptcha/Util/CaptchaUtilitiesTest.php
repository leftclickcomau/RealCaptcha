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

class CaptchaUtilitiesTest extends \PHPUnit_Framework_TestCase {

	// TODO tests for random()

	public function testGetColourComponentIntegerValue() {
		$this->assertEquals(42, CaptchaUtilities::getValueFromRange(42));
	}

	public function testGetColourComponentCorrectlyStructuredArrayValue() {
		$value = CaptchaUtilities::getValueFromRange(array( 'min' => 42, 'max' => 69 ));
		$this->assertGreaterThanOrEqual(42, $value);
		$this->assertLessThanOrEqual(69, $value);
	}

	public function testGetColourComponentIncorrectlyStructuredArrayValue() {
		try {
			CaptchaUtilities::getValueFromRange(array('not' => 'valid'));
			$this->fail('Expected exception not thrown for invalid array parameter');
		} catch (\Exception $e) {
			// Nothing
		}
	}

}
