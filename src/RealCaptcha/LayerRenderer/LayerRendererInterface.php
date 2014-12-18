<?php
/*!
 * This file is a part of RealCaptcha human verification system.
 * Copyright (c) Ben New, Leftclick.com.au
 * See the LICENSE and README files in the main source directory for details.
 *
 * Partly based on "CaptchaSecurityImages.php" by Simon Jarvis, copyright 2006, updated: 07/02/07
 * http://www.white-hat-web-design.co.uk/articles/php-captcha.php
 */

namespace RealCaptcha\LayerRenderer;

use RealCaptcha\CaptchaInterface;

/**
 * Defines the behaviour of an object responsible for drawing a single layer onto an image.
 *
 * @package RealCaptcha\Layer
 */
interface LayerRendererInterface {

	/**
	 * Draw the layer onto the given image.
	 *
	 * @param resource $image
   * @param CaptchaInterface $captcha
	 *
	 * @throws \RuntimeException
	 */
	public function render($image, CaptchaInterface $captcha);

}
