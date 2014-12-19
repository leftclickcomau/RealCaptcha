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

/**
 * Defines the behaviour of an object responsible for drawing a single layer onto an image.
 *
 * @package RealCaptcha\Layer
 */
interface GraphicsEngineInterface {

	/**
	 * Initialise the graphics engine.
	 *
	 * @param int $width
	 * @param int $height
	 * @param array $backgroundColour
	 *
	 * @return $this
	 *
	 * @throws \RuntimeException If a problem occurs during initialisation.
	 * @throws \Exception If the engine is already initialised.
	 */
	public function initialise($width, $height, array $backgroundColour);

	/**
	 * Draw a straight, solid line.
	 *
	 * @param int $x0
	 * @param int $y0
	 * @param int $x1
	 * @param int $y1
	 * @param array $colour
	 *
	 * @return $this
	 *
	 * @throws \RuntimeException If a problem occurs during drawing.
	 * @throws \Exception If the engine is not initialised.
	 */
	public function drawLine($x0, $y0, $x1, $y1, array $colour);

	/**
	 * Draw a curved arc.
	 *
	 * @param int $x
	 * @param int $y
	 * @param int $width
	 * @param int $height
	 * @param int $start
	 * @param int $end
	 * @param array $colour
	 *
	 * @return $this
	 *
	 * @throws \RuntimeException If a problem occurs during drawing.
	 * @throws \Exception If the engine is not initialised.
	 */
	public function drawArc($x, $y, $width, $height, $start, $end, array $colour);

	/**
	 * Draw a filled or empty rectangle.
	 *
	 * @param int $x0
	 * @param int $y0
	 * @param int $x1
	 * @param int $y1
	 * @param array $colour
	 * @param boolean $filled
	 *
	 * @return $this
	 *
	 * @throws \RuntimeException If a problem occurs during drawing.
	 * @throws \Exception If the engine is not initialised.
	 */
	public function drawRectangle($x0, $y0, $x1, $y1, array $colour, $filled = false);

	/**
	 * Draw a filled or empty ellipse.
	 *
	 * @param int $x
	 * @param int $y
	 * @param int $width
	 * @param int $height
	 * @param array $colour
	 * @param boolean $filled
	 *
	 * @return $this
	 *
	 * @throws \RuntimeException If a problem occurs during drawing.
	 * @throws \Exception If the engine is not initialised.
	 */
	public function drawEllipse($x, $y, $width, $height, array $colour, $filled = false);

	/**
	 * Get the dimensions of the given text with the given settings.
	 *
	 * @param string $text
	 * @param string $fontPath
	 * @param int $size
	 * @param int $angle
	 *
	 * @return array Array with two keys: `width` and `height`, each mapped to an integer value in pixels.
	 */
	public function getTextDimensions($text, $fontPath, $size, $angle = 0);

	/**
	 * Draw text.
	 *
	 * @param int $x
	 * @param int $y
	 * @param string $text
	 * @param string $fontPath
	 * @param int $size
	 * @param array $colour
	 * @param int $angle
	 *
	 * @return $this
	 *
	 * @throws \RuntimeException If a problem occurs during drawing.
	 * @throws \Exception If the engine is not initialised.
	 */
	public function drawText($x, $y, $text, $fontPath, $size, array $colour, $angle = 0);

	/**
	 * Render the composed image to standard output.  This pushes the image to the web browser.  This does not set any
	 * headers, that is the responsibility of calling code.
	 *
	 * @param string $format
	 *
	 * @return $this
	 *
	 * @throws \RuntimeException If a problem occurs rendering the image data.
	 * @throws \Exception If the engine is not initialised.
	 */
	public function render($format);

	/**
	 * Cleanup after drawing and rendering.  This should release any resources used by the graphics engine.
	 *
	 * @return $this
	 */
	public function cleanup();

}
