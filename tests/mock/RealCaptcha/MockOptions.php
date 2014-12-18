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
class MockOptions implements OptionsInterface {

  private $options;

  public function __construct(array $options = array()) {
    $this->options = $options;
  }

 	public function getOption($key) {
    return isset($this->options[$key]) ? $this->options[$key] : null;
  }

 	public function setOption($key, $value) {
    $this->options[$key] = $value;
  }

}
