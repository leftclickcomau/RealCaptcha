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

use RealCaptcha\Util\CaptchaUtilities;

/**
 * Captcha code generator which produces a random string of alpha-numeric characters, and expects the same code.
 *
 * @package RealCaptcha\CodeGenerator
 */
class AlphanumericCodeGenerator extends AbstractCodeGenerator {

	public function generateCode() {
		$length = $this->getOption('length');
		if (!is_int($length) || $length <= 0) {
			throw new \RuntimeException('Must specify an integer length greater than zero.');
		}

		$characters = $this->getOption('characters');
		if (!is_string($characters) || strlen($characters) === 0) {
			throw new \RuntimeException('Must specify at least one character to generate the code from.');
		}

		$code = array( 'display' => '' );
		for ($i=0, $max=strlen($characters)-1; $i<$length; $i++) {
			$code['display'] .= substr($characters, CaptchaUtilities::random(0, $max), 1);
		}

		$code['result'] = $code['display'];
		return $code;
	}

}
