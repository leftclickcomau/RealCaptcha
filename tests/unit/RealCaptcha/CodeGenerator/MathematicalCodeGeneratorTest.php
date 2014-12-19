<?php
/*!
 * This file is a part of RealCaptcha human verification system.
 * Copyright (c) Ben New, Leftclick.com.au
 * See the LICENSE and README files in the main source directory for details.
 *
 * Partly based on "CaptchaSecurityImages.php" by Simon Jarvis, copyright 2006, updated: 07/02/07
 * http://www.white-hat-web-design.co.uk/articles/php-captcha.php
 */

namespace RealCaptcha\CodeGenerator;

use RealCaptcha\MockOptions;

class MathematicalCodeGeneratorTest extends \PHPUnit_Framework_TestCase {

	public function testGenerateCodeValidOptions() {
		$generator = new MathematicalCodeGenerator(new MockOptions(array(
			'operators' => '+',
			'length' => 2,
			'min-value' => 0,
			'max-value' => 9
		)));

		$code = $generator->generateCode();

		$this->assertEquals(3, strlen($code['display']));
		$expectedResult = intval($code['display'][0]) + intval($code['display'][2]);
		$this->assertEquals($expectedResult, $code['result']);
		$this->assertEquals(2, sizeof($code));
		foreach (preg_split('/[^\d]/', $code['display']) as $character) {
			$this->assertGreaterThanOrEqual(0, intval($character));
			$this->assertLessThanOrEqual(9, intval($character));
		}
	}

	public function testGenerateCodeNegativeLength() {
		$generator = new AlphanumericCodeGenerator(new MockOptions(array(
			'operators' => '+-*',
			'length' => -1,
			'min' => 0,
			'max' => 9
		)));
		try {
			$generator->generateCode();
			$this->fail('Expected exception not thrown with invalid options.');
		} catch (\RuntimeException $e) {
			// Nothing
		}
	}

	public function testGenerateCodeNoOperators() {
		$generator = new AlphanumericCodeGenerator(new MockOptions(array(
			'operators' => '',
			'length' => 2,
			'min' => 0,
			'max' => 9
		)));
		try {
			$generator->generateCode();
			$this->fail('Expected exception not thrown with invalid options.');
		} catch (\RuntimeException $e) {
			// Nothing
		}
	}

}
