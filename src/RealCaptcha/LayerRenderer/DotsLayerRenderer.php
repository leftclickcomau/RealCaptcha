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

use RealCaptcha\LayerRenderer\AbstractLayerRenderer;
use RealCaptcha\RealCaptcha;
use RealCaptcha\Util\ColourUtilities;

class DotsLayerRenderer extends AbstractLayerRenderer {

	/**
	 * @inheritdoc
	 */
	public function render($image) {
		$width = $this->getCaptcha()->getOption('width');
		$height = $this->getCaptcha()->getOption('height');
		$noise = $this->getCaptcha()->getOption('noise');
		$dotsCount = max($noise['dots']['min'], min($noise['dots']['max'], ($width * $height) / $noise['dots']['divisor']));
		for ($i=0; $i<$dotsCount; $i++) {
			$x = mt_rand(0, $width);
			$y = mt_rand(0, $height);
			$colour = ColourUtilities::createColour($image, $noise['colour']);
			imagefilledellipse($image, $x, $y, 1, 1, $colour);
		}
	}

}
