<?php
/*!
 * This file is a part of RealCaptcha human verification system.
 * Copyright (c) Ben New, Leftclick.com.au
 * See the LICENSE and README files in the main source directory for details.
 *
 * Partly based on "CaptchaSecurityImages.php" by Simon Jarvis, copyright 2006, updated: 07/02/07
 * http://www.white-hat-web-design.co.uk/articles/php-captcha.php
 */

namespace RealCaptcha;

/**
 * Defines the behaviour of an options container.
 *
 * @package RealCaptcha
 */
interface CaptchaInterface {

	/**
 	 * Render the image data to standard output.
 	 *
 	 * @throws \RuntimeException
 	 */
 	public function writeImage();

 	/**
 	 * Generate the code for this captcha, and store it in the session.
 	 *
 	 * @return string
 	 *
 	 * @throws \InvalidArgumentException
 	 */
 	public function generateCode();

 	/**
 	 * Compare the specified code against the code stored in the session, and remove the code from the session as it
 	 * should only ever be checked once.
 	 *
 	 * @param string $code
 	 *
 	 * @return boolean
 	 */
 	public function checkCode($code);

}
