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
 * Captcha code generator which displays a randomly generated mathematical equation, and expects the answer.
 *
 * @package RealCaptcha\CodeGenerator
 */
class MathematicalCodeGenerator extends AbstractCodeGenerator {

	public function generateCode() {
    $length = $this->getOption('length');
    if (!is_int($length) || $length <= 0) {
      throw new \RuntimeException('Must specify an integer length greater than zero.');
    }

    $operators = $this->getOption('operators');
    if (strlen($operators) === 0) {
      throw new \RuntimeException('Cannot generate a mathematical expression without operators.');
    }

    $min = $this->getOption('min-value');
    $max = $this->getOption('max-value');

		$components = array();
		for ($i=0, $l=$length; $i<$l; $i++) {
			$components[] = CaptchaUtilities::random($min, $max);
			if ($i < $l - 1) {
				$components[] = substr($operators, CaptchaUtilities::random(0, strlen($operators) - 1), 1);
			}
		}
		$code = array( 'display' => implode('', $components) );

		eval('$code["result"] = ' . $code['display'] . ';');
		return $code;
	}

}
