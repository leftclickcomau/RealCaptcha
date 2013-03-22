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
 * Defines the behaviour of an object responsible for creating captcha codes, i.e. the value displayed and the value
 * typed in, which may or may not be the same depending on the type of captcha.
 *
 * @package RealCaptcha\CodeGenerator
 */
interface CodeGeneratorInterface {

	/**
	 * Generate the code
	 *
	 * @return string
	 */
	public function generateCode();

}
