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
use RealCaptcha\Util\CaptchaUtilities;

class CodeLayerRenderer extends AbstractLayerRenderer {

	/**
	 * @inheritdoc
	 */
	public function render(GraphicsEngineInterface $graphicsEngine, CaptchaInterface $captcha) {
		$width = $this->getOption('width');
		$height = $this->getOption('height');
		$text = $this->getOption('text');
		$angle = CaptchaUtilities::random($text['angle']['min'], $text['angle']['max']);
		$font = is_array($text['font']) ? $text['font'][CaptchaUtilities::random(0, sizeof($text['font']) - 1)] : $text['font'];
		$paths = $this->getOption('paths');
		$fontPath = sprintf('%s/%s.ttf', $paths['font'], $font);
		$fontSize = min($height * $text['font-size-ratio']['height'], $width * $text['font-size-ratio']['width']);
		$code = $captcha->generateCode();
		$textBoundingBox = $graphicsEngine->getTextDimensions($code['display'], $fontPath, $fontSize, $angle);
		$x = ($width - $textBoundingBox['width']) / 2;
		$y = ($height - $textBoundingBox['height']) / 2;
		$graphicsEngine->drawText($x, $y, $code['display'], $fontPath, $fontSize, $text['colour'], $angle);
	}

}
