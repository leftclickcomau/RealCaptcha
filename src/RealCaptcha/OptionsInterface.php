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
interface OptionsInterface {

  /**
 	 * Retrieve the option with the given key.
 	 *
 	 * @param string $key
 	 *
 	 * @return mixed|null
 	 */
 	public function getOption($key);

 	/**
 	 * Set the option with the given key to the specified value.
 	 *
 	 * @param string $key
 	 * @param mixed $value
 	 */
 	public function setOption($key, $value);

}
