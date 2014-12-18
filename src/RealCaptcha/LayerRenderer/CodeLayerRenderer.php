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
use RealCaptcha\Util\ColourUtilities;

class CodeLayerRenderer extends AbstractLayerRenderer {

	/**
	 * @inheritdoc
	 */
	public function render($image, CaptchaInterface $captcha) {
		$width = $this->getOption('width');
		$height = $this->getOption('height');
		$text = $this->getOption('text');
		$angle = mt_rand($text['angle']['min'], $text['angle']['max']);
		$font = is_array($text['font']) ? $text['font'][mt_rand(0, sizeof($text['font']) - 1)] : $text['font'];
		$paths = $this->getOption('paths');
		$fontPath = sprintf('%s/%s.ttf', $paths['font'], $font);
		$fontSize = min($height * $text['font-size-ratio']['height'], $width * $text['font-size-ratio']['width']);
		$code = $captcha->generateCode();
		if (!($textBoundingBox = imagettfbbox($fontSize, $angle, $fontPath, $code['display']))) {
			throw new \RuntimeException('RealCaptcha encountered an error calling imagettfbbox() function.');
		}
		$x = ($width - $textBoundingBox[4]) / 2;
		$y = ($height - $textBoundingBox[5]) / 2;
		$colour = ColourUtilities::createColour($image, $text['colour']);
		if (!imagettftext($image, $fontSize, $angle, $x, $y, $colour, $fontPath , $code['display'])) {
			throw new \RuntimeException('RealCaptcha encountered an error calling imagettftext() function.');
		}
	}

}
