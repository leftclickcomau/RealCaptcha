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

class ShapesLayerRenderer extends AbstractLayerRenderer {

	/**
	 * @inheritdoc
	 */
	public function render(GraphicsEngineInterface $graphicsEngine, CaptchaInterface $captcha) {
		$width = $this->getOption('width');
		$height = $this->getOption('height');
		$noise = $this->getOption('noise');
		$shapesCount = max($noise['shapes']['min'], min($noise['shapes']['max'], ($width * $height) / $noise['shapes']['divisor']));
		for ($i=0; $i<$shapesCount; $i++) {
			switch ($this->selectShape($noise['shapes']['distribution'])) {
				case 'rectangle':
					$graphicsEngine->drawRectangle(
						CaptchaUtilities::random(0, $width),
						CaptchaUtilities::random(0, $height),
						CaptchaUtilities::random(0, $width),
						CaptchaUtilities::random(0, $height),
						$noise['colour']
					);
					break;
				case 'ellipse':
					$graphicsEngine->drawEllipse(
						CaptchaUtilities::random(0, $width),
						CaptchaUtilities::random(0, $height),
						CaptchaUtilities::random(0, intval($width / $noise['shapes']['size-divisor'])),
						CaptchaUtilities::random(0, intval($height / $noise['shapes']['size-divisor'])),
						$noise['colour']
					);
					break;
				case 'arc':
					$graphicsEngine->drawArc(
						CaptchaUtilities::random(0, $width),
						CaptchaUtilities::random(0, $height),
						CaptchaUtilities::random(0, intval($width / $noise['shapes']['size-divisor'])),
						CaptchaUtilities::random(0, intval($height / $noise['shapes']['size-divisor'])),
						CaptchaUtilities::random(0, 360),
						CaptchaUtilities::random(0, 360),
						$noise['colour']
					);
					break;
			}
		}
	}

	//-- Internal Methods --------------------

	/**
	 * Select a random shape based on the given distribution.
	 *
	 * @param array $distribution
	 *
	 * @return string
	 */
	protected function selectShape($distribution) {
		$rand = CaptchaUtilities::random(0, array_sum(array_map(function($item) { return $item['weight']; }, $distribution)) - 1);
		while ($rand >= $distribution[0]['weight']) {
			$distributionItem = array_shift($distribution);
			$rand -= $distributionItem['weight'];
		}
		return $distribution[0]['shape'];
	}

}
