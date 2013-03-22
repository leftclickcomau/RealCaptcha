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

/**
 * Captcha code generator which produces a random string of alpha-numeric characters, and expects the same code.
 *
 * @package RealCaptcha\CodeGenerator
 */
class AlphanumericCodeGenerator extends AbstractCodeGenerator {

	public function generateCode() {
		$code = array( 'display' => '' );
		for ($i=0, $max=strlen($this->getCaptcha()->getOption('characters'))-1; $i<$this->getCaptcha()->getOption('length'); $i++) {
			$code['display'] .= substr($this->getCaptcha()->getOption('characters'), mt_rand(0, $max), 1);
		}
		$code['result'] = $code['display'];
		return $code;
	}

}
