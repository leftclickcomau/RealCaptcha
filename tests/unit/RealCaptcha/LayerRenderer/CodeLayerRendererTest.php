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

class CodeLayerRendererTest extends \PHPUnit_Framework_TestCase {

	public function testRenderValidOptions() {
		/** @var GraphicsEngineInterface $graphicsEngine */
		$graphicsEngine = $this->getMockBuilder('\RealCaptcha\GraphicsEngine\GraphicsEngineInterface')->getMock();
		$graphicsEngine->method('getTextDimensions')->will($this->returnValue(array(
			'width' => 100,
			'height' => 100
		)));
		$graphicsEngine->expects($this->once())->method('drawText')->with(
			250,
			160,
			'test',
			$this->logicalOr($this->equalTo('/fonts/font-1.ttf'), $this->equalTo('/fonts/font-2.ttf')),
			$this->equalTo(120),
			array( 'red' => 0, 'green' => 0, 'blue' => 0 ),
			$this->logicalAnd($this->greaterThanOrEqual(-15), $this->lessThanOrEqual(15))
		);

		/** @var CaptchaInterface $captcha */
		$captcha = $this->getMockBuilder('\RealCaptcha\CaptchaInterface')->getMock();
		$captcha->method('generateCode')->will($this->returnValue(array(
			'result' => 'test',
			'display' => 'test'
		)));

		$renderer = new CodeLayerRenderer(new MockOptions(array(
			'width' => 600,
			'height' => 420,
			'paths' => array(
				'font' => '/fonts'
			),
			'text' => array(
				'colour' => array(
					'red' => 0,
					'green' => 0,
					'blue' => 0
				),
				'font' => array(
					'font-1',
					'font-2'
				),
				'font-size-ratio' => array(
					'width' => 0.2,
					'height' => 0.5
				),
				'angle' => array(
					'min' => -15,
					'max' => 15
				)
			)
		)));
		$renderer->render($graphicsEngine, $captcha);
	}

}
