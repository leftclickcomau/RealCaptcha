<?php
/*!
 * This file is a part of RealCaptcha human verification system.
 * Copyright (c) Ben New, Leftclick.com.au
 * See the LICENSE and README files in the main source directory for details.
 *
 * Partly based on "CaptchaSecurityImages.php" by Simon Jarvis, copyright 2006, updated: 07/02/07
 * http://www.white-hat-web-design.co.uk/articles/php-captcha.php
 */

namespace RealCaptcha;

/**
 * RealCaptcha provides an easy to implement captcha mechanism for any type of web form.
 *
 * @package RealCaptcha
 */
class RealCaptcha {

	//-- Constants --------------------

	/**
	 * Captcha type that displays a given number of alphanumeric characters and requests the user type them in.
	 */
	const TYPE_ALPHANUMERIC = 'alphanumeric';

	/**
	 * Captcha type that displays a simple mathematical expression (addition, subtraction, multiplication or division)
	 * and requests the user type the correct answer.
	 */
	const TYPE_MATHEMATICAL = 'mathematical';

	/**
	 * Relative path to the default configuration file.
	 */
	const PATH_DEFAULTS = '../../config/defaults.php';

	/**
	 * Base session key, used on its own or after a namespace prefix is one is set.
	 */
	const SESSION_KEY_BASE_CODE = 'code';

	//-- Attributes --------------------

	/**
	 * Must be one of the `TYPE_*` constants defined in this class.
	 *
	 * @var string
	 */
	private $type;

	/**
	 * @var array
	 */
	private $options;

	/**
	 * @var string
	 */
	private $code;

	//-- Constructor --------------------

	/**
	 * @param string $type
	 * @param array|null $options
	 *
	 * @throws \RuntimeException
	 */
	public function __construct($type, array $options=null) {
		// Set type and options.
		$this->type = $type;
		$this->options = $this->mergeOptions($options);
		// Generate the code and store it in the session.
		$this->code = $this->generateCode();
		$namespace = $this->getOption('namespace');
		$sessionKey = empty($namespace) ? self::SESSION_KEY_BASE_CODE : sprintf('%s.%s', $namespace, self::SESSION_KEY_BASE_CODE);
		$this->initSession();
		$_SESSION[$sessionKey] = $this->code;
	}

	//-- Public Methods --------------------

	/**
	 * Get the type of captcha being generated.
	 *
	 * @return string
	 */
	public function getType() {
		return $this->type;
	}

	/**
	 * Retrieve the option with the given key.
	 *
	 * @param string $key
	 *
	 * @return mixed|null
	 */
	public function getOption($key) {
		return isset($this->options[$key]) ? $this->options[$key] : null;
	}

	/**
	 * Set the option with the given key to the specified value.
	 *
	 * @param string $key
	 * @param mixed $value
	 */
	public function setOption($key, $value) {
		$this->options[$key] = $value;
	}

	/**
	 * Write the JPEG image data to standard output.
	 *
	 * @throws \RuntimeException
	 */
	public function writeImage() {
		// Create the image.
		$width = $this->getOption('width');
		$height = $this->getOption('height');
		if (!($image = imagecreate($width, $height))) {
			throw new \RuntimeException('RealCaptcha could not initialise GD image stream');
		}
		// Generate the image contents according to the render sequence.
		$this->prepareImage($image);
		foreach ($this->getOption('render-sequence') as $item) {
			$methodName = sprintf('draw%s', ucfirst($item));
			$this->$methodName($image);
		}
		// Output to the browser.
		$this->outputImage($image);
	}

	//-- Internal Methods --------------------

	/**
	 * Initialise the session, if it needs to be and it is possible.
	 *
	 * @throws \RuntimeException
	 */
	protected function initSession() {
		/** @noinspection PhpVoidFunctionResultUsedInspection Issue with PhpStorm 6.0 */
		switch (session_status()) {
			case PHP_SESSION_ACTIVE:
				// Do nothing, session is already started.
				break;
			case PHP_SESSION_NONE:
				session_start();
				break;
			case PHP_SESSION_DISABLED:
				throw new \RuntimeException('RealCaptcha requires sessions to be enabled.');
		}
	}

	/**
	 * Merge the options into the defaults including per-type defaults.
	 *
	 * @param array|null $options
	 *
	 * @return array
	 */
	protected function mergeOptions(array $options=null) {
		$defaults = require sprintf('%s/%s', __DIR__, self::PATH_DEFAULTS);
		return array_merge(
			$defaults['base'],
			$defaults[$this->getType()],
			$options ?: array()
		);
	}

	/**
	 * Generate the code for this captcha.
	 *
	 * @return string
	 *
	 * @throws \InvalidArgumentException
	 */
	protected function generateCode() {
		$code = '';
		switch ($this->getType()) {
			case self::TYPE_ALPHANUMERIC:
				$characters = $this->getOption('characters');
				for ($i=0, $l=$this->getOption('length'), $max = strlen($characters)-1; $i<$l; $i++) {
					$code .= substr($characters, mt_rand(0, $max), 1);
				}
				break;

			case self::TYPE_MATHEMATICAL:
				// TODO implement mathematical expression captchas
				throw new \InvalidArgumentException('RealCaptcha mathematical type not implemented.');
				break;
		}
		return $code;
	}

	/**
	 * Create a colour reference for the given image, using the given colour which is an array containing 'red',
	 * 'green' and 'blue' keys, each of which has an integer value in the range 0-255.
	 *
	 * @param resource $image
	 * @param array $colour
	 *
	 * @return integer
	 */
	protected function createColour($image, array $colour) {
		return imagecolorallocate($image, $this->getColourComponent($colour['red']), $this->getColourComponent($colour['green']), $this->getColourComponent($colour['blue']));
	}

	/**
	 * Get a single colour component (red, green or blue), including conversion from an array containing 'min' and
	 * 'max' keys, to a single random value.
	 *
	 * @param integer|array $value
	 *
	 * @return int
	 */
	protected function getColourComponent($value) {
		if (is_array($value)) {
			$value = mt_rand($value['min'], $value['max']);
		}
		return $value;
	}

	/**
	 * Prepare the image for drawing.
	 *
	 * @param resource $image
	 */
	protected function prepareImage($image) {
		$width = $this->getOption('width');
		$height = $this->getOption('height');
		$colour = $this->createColour($image, $this->getOption('background-colour'));
		imagefilledrectangle($image, 0, 0, $width, $height, $colour);
	}

	/**
	 * Draw the text of the code itself.
	 *
	 * @param resource $image
	 *
	 * @throws \RuntimeException
	 */
	protected function drawCode($image) {
		$width = $this->getOption('width');
		$height = $this->getOption('height');
		$text = $this->getOption('text');
		$angle = mt_rand($text['angle']['min'], $text['angle']['max']);
		$font = is_array($text['font']) ? $text['font'][mt_rand(0, sizeof($text['font']) - 1)] : $text['font'];
		$fontPath = sprintf('%s/%s.ttf', $this->getOption('paths')['font'], $font);
		$fontSize = min($height * $text['font-size-ratio']['height'], $width * $text['font-size-ratio']['width']);
		if (!($textBoundingBox = imagettfbbox($fontSize, $angle, $fontPath, $this->code))) {
			throw new \RuntimeException('RealCaptcha encountered an error calling imagettfbbox() function.');
		}
		$x = ($width - $textBoundingBox[4]) / 2;
		$y = ($height - $textBoundingBox[5]) / 2;
		$colour = $this->createColour($image, $text['colour']);
		if (!imagettftext($image, $fontSize, $angle, $x, $y, $colour, $fontPath , $this->code)) {
			throw new \RuntimeException('RealCaptcha encountered an error calling imagettftext() function.');
		}
	}

	/**
	 * Draw dots as noise.
	 *
	 * @param resource $image
	 */
	protected function drawDots($image) {
		$width = $this->getOption('width');
		$height = $this->getOption('height');
		$noise = $this->getOption('noise');
		$dotsCount = max($noise['dots']['min'], min($noise['dots']['max'], ($width * $height) / $noise['dots']['divisor']));
		for ($i=0; $i<$dotsCount; $i++) {
			$x = mt_rand(0, $width);
			$y = mt_rand(0, $height);
			$colour = $this->createColour($image, $noise['colour']);
			imagefilledellipse($image, $x, $y, 1, 1, $colour);
		}
	}

	/**
	 * Draw lines as noise.
	 *
	 * @param resource $image
	 */
	protected function drawLines($image) {
		$width = $this->getOption('width');
		$height = $this->getOption('height');
		$noise = $this->getOption('noise');
		$linesCount = max($noise['lines']['min'], min($noise['lines']['max'], ($width * $height) / $noise['lines']['divisor']));
		for ($i=0; $i<$linesCount; $i++) {
			$x0 = mt_rand(0, $width);
			$y0 = mt_rand(0, $height);
			$x1 = mt_rand(0, $width);
			$y1 = mt_rand(0, $height);
			$colour = $this->createColour($image, $noise['colour']);
			imageline($image, $x0, $y0, $x1, $y1, $colour);
		}
	}

	/**
	 * Draw random shapes as noise.
	 *
	 * @param resource $image
	 */
	protected function drawShapes($image) {
		$width = $this->getOption('width');
		$height = $this->getOption('height');
		$noise = $this->getOption('noise');
		$shapesCount = max($noise['shapes']['min'], min($noise['shapes']['max'], ($width * $height) / $noise['shapes']['divisor']));
		for ($i=0; $i<$shapesCount; $i++) {
			$colour = $this->createColour($image, $noise['colour']);
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

	/**
	 * Send the headers and image contents to the browser.
	 *
	 * @param resource $image
	 */
	protected function outputImage($image) {
		header('Content-Type: image/jpeg');
		header('Cache-Control: no-cache');
		header('Expires: ' . date('r'));
		imagejpeg($image);
		imagedestroy($image);
	}

}
