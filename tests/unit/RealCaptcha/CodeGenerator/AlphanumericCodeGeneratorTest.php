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

use RealCaptcha\MockOptions;

class AlphanumericCodeGeneratorTest extends \PHPUnit_Framework_TestCase {

  public function testGenerateCodeValidOptions() {
    $generator = new AlphanumericCodeGenerator(new MockOptions(array(
      'characters' => 'abcABC',
      'length' => 10
    )));
    $code = $generator->generateCode();
    $this->assertEquals(10, strlen($code['result']));
    $this->assertSame($code['result'], $code['display']);
    $this->assertEquals(2, sizeof($code));
    foreach (str_split($code['result']) as $character) {
      $this->assertGreaterThanOrEqual(0, strpos('abcABC', $character));
    }
  }

  public function testGenerateCodeNoCharacters() {
    $generator = new AlphanumericCodeGenerator(new MockOptions(array(
      'characters' => '',
      'length' => 10
    )));
    try {
      $generator->generateCode();
      $this->fail('Expected exception not thrown with invalid options.');
    } catch (\RuntimeException $e) {

    }
  }

  public function testGenerateCodeZeroLength() {
    $generator = new AlphanumericCodeGenerator(new MockOptions(array(
      'characters' => 'abcABC',
      'length' => 0
    )));
    try {
      $generator->generateCode();
      $this->fail('Expected exception not thrown with invalid options.');
    } catch (\RuntimeException $e) {

    }
  }

  public function testGenerateCodeNegativeLength() {
    $generator = new AlphanumericCodeGenerator(new MockOptions(array(
      'characters' => 'abcABC',
      'length' => -1
    )));
    try {
      $generator->generateCode();
      $this->fail('Expected exception not thrown with invalid options.');
    } catch (\RuntimeException $e) {

    }
  }

}
