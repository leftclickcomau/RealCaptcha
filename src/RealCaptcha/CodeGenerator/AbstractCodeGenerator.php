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

use RealCaptcha\OptionsInterface;

/**
 * Abstract implementation of CodeGeneratorInterface, providing access to options via the RealCaptcha object.
 *
 * @package RealCaptcha\CodeGenerator
 */
abstract class AbstractCodeGenerator implements CodeGeneratorInterface {

	//-- Attributes --------------------

	/**
	 * @var OptionsInterface
	 */
	private $options;

	//-- Constructor --------------------

	/**
	 * @param OptionsInterface $options
	 */
	public function __construct(OptionsInterface $options) {
		$this->options = $options;
	}

	//-- Internal Methods --------------------

	/**
	 * Proxy to the getOption() method in the OptionsInterface implementation.
   *
   * @param string $key
   *
   * @return mixed
	 */
	protected function getOption($key) {
		return $this->options->getOption($key);
	}

}
