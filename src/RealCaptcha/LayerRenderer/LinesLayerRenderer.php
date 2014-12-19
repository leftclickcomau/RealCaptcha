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

class LinesLayerRenderer extends AbstractLayerRenderer {

	/**
	 * @inheritdoc
	 */
	public function render(GraphicsEngineInterface $graphicsEngine, CaptchaInterface $captcha) {
		$width = $this->getOption('width');
		$height = $this->getOption('height');
		$noise = $this->getOption('noise');
		$linesCount = max($noise['lines']['min'], min($noise['lines']['max'], ($width * $height) / $noise['lines']['divisor']));
		for ($i=0; $i<$linesCount; $i++) {
			$graphicsEngine->drawLine(
				CaptchaUtilities::random(0, $width),
				CaptchaUtilities::random(0, $height),
				CaptchaUtilities::random(0, $width),
				CaptchaUtilities::random(0, $height),
				$noise['colour']
			);
		}
	}

}
