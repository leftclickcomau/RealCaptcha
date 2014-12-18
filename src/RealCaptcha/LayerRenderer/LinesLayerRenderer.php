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

class LinesLayerRenderer extends AbstractLayerRenderer {

	/**
	 * @inheritdoc
	 */
	public function render($image, CaptchaInterface $captcha) {
		$width = $this->getOption('width');
		$height = $this->getOption('height');
		$noise = $this->getOption('noise');
		$linesCount = max($noise['lines']['min'], min($noise['lines']['max'], ($width * $height) / $noise['lines']['divisor']));
		for ($i=0; $i<$linesCount; $i++) {
			$x0 = mt_rand(0, $width);
			$y0 = mt_rand(0, $height);
			$x1 = mt_rand(0, $width);
			$y1 = mt_rand(0, $height);
			$colour = ColourUtilities::createColour($image, $noise['colour']);
			imageline($image, $x0, $y0, $x1, $y1, $colour);
		}
	}

}
