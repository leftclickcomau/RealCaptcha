<?php
/*!
 * This file is a part of RealCaptcha human verification system.
 * Copyright (c) Ben New, Leftclick.com.au
 * See the LICENSE and README files in the main source directory for details.
 *
 * Partly based on "CaptchaSecurityImages.php" by Simon Jarvis, copyright 2006, updated: 07/02/07
 * http://www.white-hat-web-design.co.uk/articles/php-captcha.php
 */

namespace RealCaptcha\GraphicsEngine;

use RealCaptcha\Util\CaptchaUtilities;

/**
 * Graphics engine that uses the GD library for its low-level drawing operations.
 *
 * @package RealCaptcha\Layer
 */
class GDGraphicsEngine implements GraphicsEngineInterface {

	//-- Attributes --------------------

	private $image;

	//-- GraphicsEngineInterface Methods --------------------

	public function initialise($width, $height, array $backgroundColour) {
		if ($this->image) {
			throw new \Exception('GDGraphicsEngine already initialised');
		}
		$this->image = imagecreate($width, $height);
		if (!$this->image) {
			throw new \RuntimeException('GDGraphicsEngine could not create image');
		}
		if (!imagefilledrectangle($this->image, 0, 0, $width, $height, self::createColour($this->image, $backgroundColour))) {
			throw new \RuntimeException('GDGraphicsEngine could not fill background of image');
		}
		return $this;
	}

	public function drawLine($x0, $y0, $x1, $y1, array $colour) {
		if (!$this->image) {
			throw new \Exception('GDGraphicsEngine not initialised');
		}
		if (!imageline($this->image, $x0, $y0, $x1, $y1, self::createColour($this->image, $colour))) {
			throw new \RuntimeException('GDGraphicsEngine could not draw line');
		}
		return $this;
	}

	public function drawArc($x, $y, $width, $height, $start, $end, array $colour) {
		if (!$this->image) {
			throw new \Exception('GDGraphicsEngine not initialised');
		}
		if (!imagearc($this->image, $x, $y, $width, $height, $start, $end, self::createColour($this->image, $colour))) {
			throw new \RuntimeException('GDGraphicsEngine could not draw arc');
		}
		return $this;
	}

	public function drawRectangle($x0, $y0, $x1, $y1, array $colour, $filled = false) {
		if (!$this->image) {
			throw new \Exception('GDGraphicsEngine not initialised');
		}
		$function = $filled ? 'imagefilledrectangle' : 'imagerectangle';
		if (!$function($this->image, $x0, $y0, $x1, $y1, self::createColour($this->image, $colour))) {
			throw new \RuntimeException('GDGraphicsEngine could not draw rectangle');
		}
		return $this;
	}

	public function drawEllipse($x, $y, $width, $height, array $colour, $filled = false) {
		if (!$this->image) {
			throw new \Exception('GDGraphicsEngine not initialised');
		}
		error_log("drawEllipse($x, $y, $width, $height, [colour], $filled)");
		$function = $filled ? 'imagefilledellipse' : 'imageellipse';
		if (!$function($this->image, $x, $y, $width, $height, self::createColour($this->image, $colour))) {
			throw new \RuntimeException('GDGraphicsEngine could not draw ellipse');
		}
		return $this;
	}

	public function getTextDimensions($text, $fontPath, $size, $angle = 0) {
		if (!$this->image) {
			throw new \Exception('GDGraphicsEngine not initialised');
		}
		$box = imagettfbbox($size, $angle, $fontPath, $text);
		if (!$box) {
			throw new \RuntimeException('GDGraphicsEngine could not determine text dimensions');
		}
		return array(
			'width' => $box[2],
			'height' => $box[3]
		);
	}

	public function drawText($x, $y, $text, $fontPath, $size, array $colour, $angle = 0) {
		if (!$this->image) {
			throw new \Exception('GDGraphicsEngine not initialised');
		}
		if (!imagettftext($this->image, $size, $angle, $x, $y, self::createColour($this->image, $colour), $fontPath, $text)) {
			throw new \RuntimeException('GDGraphicsEngine could not draw text');
		}
		return $this;
	}

	public function render($format) {
		$function = 'image' . $format;
		if (!function_exists($function)) {
			throw new \RuntimeException('GDGraphicsEngine encountered unknown rendering format "' . $format . '"');
		}
		if (!$function($this->image)) {
			throw new \RuntimeException('GDGraphicsEngine could not render image in "' . $format . '" format');
		}
		return $this;
	}

	public function cleanup() {
		imagedestroy($this->image);
		$this->image = null;
		return $this;
	}

	//-- Internal Methods --------------------

	/**
	 * Create a colour reference for the given image, using the given colour which is an array containing 'red',
	 * 'green' and 'blue' keys, each of which has an integer value in the range 0-255.
	 *
	 * @param resource $image
	 * @param array $colour
	 *
	 * @return integer
	 */
	private static function createColour($image, array $colour) {
		return imagecolorallocate(
			$image,
			CaptchaUtilities::getValueFromRange($colour['red']),
			CaptchaUtilities::getValueFromRange($colour['green']),
			CaptchaUtilities::getValueFromRange($colour['blue'])
		);
	}

}
