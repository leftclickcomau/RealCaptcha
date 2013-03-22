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

class ShapesLayerRenderer extends AbstractLayerRenderer {

	/**
	 * @inheritdoc
	 */
	public function render($image) {
		$width = $this->getCaptcha()->getOption('width');
		$height = $this->getCaptcha()->getOption('height');
		$noise = $this->getCaptcha()->getOption('noise');
		$shapesCount = max($noise['shapes']['min'], min($noise['shapes']['max'], ($width * $height) / $noise['shapes']['divisor']));
		for ($i=0; $i<$shapesCount; $i++) {
			$colour = ColourUtilities::createColour($image, $noise['colour']);
			switch ($this->selectShape($noise['shapes']['distribution'])) {
				case 'rectangle':
					$cx = mt_rand(0, $width);
					$cy = mt_rand(0, $height);
					$x2 = mt_rand(0, $width);
					$y2 = mt_rand(0, $height);
					imagerectangle($image, $cx, $cy, $x2, $y2, $colour);
					break;
				case 'ellipse':
					$cx = mt_rand(0, $width);
					$cy = mt_rand(0, $height);
					$width = mt_rand(0, $width / $noise['shapes']['size-divisor']);
					$height = mt_rand(0, $height / $noise['shapes']['size-divisor']);
					imageellipse($image, $cx, $cy, $width, $height, $colour);
					break;
				case 'arc':
					$cx = mt_rand(0, $width);
					$cy = mt_rand(0, $height);
					$width = mt_rand(0, $width / $noise['shapes']['size-divisor']);
					$height = mt_rand(0, $height / $noise['shapes']['size-divisor']);
					$start = mt_rand(0, 360);
					$end = mt_rand(0, 360);
					imagearc($image, $cx, $cy, $width, $height, $start, $end, $colour);
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
		$rand = mt_rand(0, array_sum(array_map(function($item) { return $item['weight']; }, $distribution)) - 1);
		while ($rand >= $distribution[0]['weight']) {
			$rand -= array_shift($distribution)['weight'];
		}
		return $distribution[0]['shape'];
	}

}
