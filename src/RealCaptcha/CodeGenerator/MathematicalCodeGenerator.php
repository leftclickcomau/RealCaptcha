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
 * Captcha code generator which displays a randomly generated mathematical equation, and expects the answer.
 *
 * @package RealCaptcha\CodeGenerator
 */
class MathematicalCodeGenerator extends AbstractCodeGenerator {

	public function generateCode() {
		$components = array();
		for ($i=0, $l=$this->getCaptcha()->getOption('length'); $i<$l; $i++) {
			$components[] = mt_rand($this->getCaptcha()->getOption('min-value'), $this->getCaptcha()->getOption('max-value'));
			if ($i < $l - 1) {
				$components[] = substr($this->getCaptcha()->getOption('operators'), mt_rand(0, 2), 1);
			}
		}
		$code = array( 'display' => implode('', $components) );
		eval('$code["result"] = ' . $code['display'] . ';');
		return $code;
	}

}
