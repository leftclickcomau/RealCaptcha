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

class LinesLayerRendererTest extends \PHPUnit_Framework_TestCase {

	public function testRenderValidOptions() {
		/** @var GraphicsEngineInterface $graphicsEngine */
		$graphicsEngine = $this->getMockBuilder('\RealCaptcha\GraphicsEngine\GraphicsEngineInterface')->getMock();
		$graphicsEngine->expects($this->atLeast(0))->method('drawLine')->with(
			$this->logicalAnd($this->greaterThanOrEqual(0), $this->lessThanOrEqual(600)),
			$this->logicalAnd($this->greaterThanOrEqual(0), $this->lessThanOrEqual(420)),
			$this->logicalAnd($this->greaterThanOrEqual(0), $this->lessThanOrEqual(600)),
			$this->logicalAnd($this->greaterThanOrEqual(0), $this->lessThanOrEqual(420)),
			array( 'red' => 255, 'green' => 255, 'blue' => 255 )
		);
		$graphicsEngine->expects($this->atMost(10))->method('drawLine')->with(
			$this->logicalAnd($this->greaterThanOrEqual(0), $this->lessThanOrEqual(600)),
			$this->logicalAnd($this->greaterThanOrEqual(0), $this->lessThanOrEqual(420)),
			$this->logicalAnd($this->greaterThanOrEqual(0), $this->lessThanOrEqual(600)),
			$this->logicalAnd($this->greaterThanOrEqual(0), $this->lessThanOrEqual(420)),
			array( 'red' => 255, 'green' => 255, 'blue' => 255 )
		);

		/** @var CaptchaInterface $captcha */
		$captcha = $this->getMockBuilder('\RealCaptcha\CaptchaInterface')->getMock();

		$renderer = new LinesLayerRenderer(new MockOptions(array(
			'width' => 600,
			'height' => 420,
			'noise' => array(
				'lines' => array(
					'divisor' => 250,
					'min' => 0,
					'max' => 10
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
