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
use RealCaptcha\GraphicsEngine\GraphicsEngineInterface;
use RealCaptcha\MockOptions;

class DotsLayerRendererTest extends \PHPUnit_Framework_TestCase {

	public function testRenderValidOptions() {
		/** @var GraphicsEngineInterface $graphicsEngine */
		$graphicsEngine = $this->getMockBuilder('\RealCaptcha\GraphicsEngine\GraphicsEngineInterface')->getMock();
		$graphicsEngine->expects($this->atLeast(0))->method('drawEllipse')->with(
			$this->logicalAnd($this->greaterThanOrEqual(0), $this->lessThanOrEqual(600)),
			$this->logicalAnd($this->greaterThanOrEqual(0), $this->lessThanOrEqual(420)),
			1,
			1,
			array( 'red' => 255, 'green' => 255, 'blue' => 255 ),
			true
		);
		$graphicsEngine->expects($this->atMost(100))->method('drawEllipse')->with(
			$this->logicalAnd($this->greaterThanOrEqual(0), $this->lessThanOrEqual(600)),
			$this->logicalAnd($this->greaterThanOrEqual(0), $this->lessThanOrEqual(420)),
			1,
			1,
			array( 'red' => 255, 'green' => 255, 'blue' => 255 ),
			true
		);

		/** @var CaptchaInterface $captcha */
		$captcha = $this->getMockBuilder('\RealCaptcha\CaptchaInterface')->getMock();

		$renderer = new DotsLayerRenderer(new MockOptions(array(
			'width' => 600,
			'height' => 420,
			'noise' => array(
				'dots' => array(
					'divisor' => 3,
					'min' => 0,
					'max' => 100
				),
				'colour' => array(
					'red' => 255,
					'green' => 255,
					'blue' => 255
				)
			)
		)));
		$renderer->render($graphicsEngine, $captcha);
	}

}
