<?php
/*!
 * This file is a part of RealCaptcha human verification system.
 * Copyright (c) Ben New, Leftclick.com.au
 * See the LICENSE and README files in the main source directory for details.
 */

namespace RealCaptcha;

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
		$this->type = $type;
		$this->options = $this->mergeOptions($options);
		$this->code = $this->generateCode();
		$this->initSession();
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
		// Create the code.
		$width = $this->getOption('width');
		$height = $this->getOption('height');
		$fontSize = $height * $this->getOption('font-size-ratio');
		if (!($image = imagecreate($width, $height))) {
			throw new \RuntimeException('RealCaptcha could not initialise GD image stream');
		}
		// Set the colours.
		$backgroundColour = $this->createColour($image, $this->getOption('background-colour'));
		$textColour = $this->createColour($image, $this->getOption('text-colour'));
		$noiseColour = $this->createColour($image, $this->getOption('noise-colour'));
		// Fill the background.
		imagefilledrectangle($image, 0, 0, $width, $height, $backgroundColour);
		// Generate dots for noise.
		$dots = $this->getOption('noise-dots');
		$dotsCount = max($dots['min'], min($dots['max'], ($width * $height) / $dots['divisor']));
		for ($i=0; $i<$dotsCount; $i++) {
			$x = mt_rand(0, $width);
			$y = mt_rand(0, $height);
			imagefilledellipse($image, $x, $y, 1, 1, $noiseColour);
		}
		// Generate lines for noise.
		$lines = $this->getOption('noise-lines');
		$linesCount = max($lines['min'], min($lines['max'], ($width * $height) / $lines['divisor']));
		for ($i=0; $i<$linesCount; $i++) {
			$x0 = mt_rand(0, $width);
			$y0 = mt_rand(0, $height);
			$x1 = mt_rand(0, $width);
			$y1 = mt_rand(0, $height);
			imageline($image, $x0, $y0, $x1, $y1, $noiseColour);
		}
		// Create a text bounding box and add text.
		$font = $this->getOption('font');
		if (!($textBoundingBox = imagettfbbox($fontSize, 0, $font, $this->code))) {
			throw new \RuntimeException('RealCaptcha encountered an error calling imagettfbbox() function.');
		}
		$x = ($width - $textBoundingBox[4]) / 2;
		$y = ($height - $textBoundingBox[5]) / 2;
		if (!imagettftext($image, $fontSize, 0, $x, $y, $textColour, $font , $this->code)) {
			throw new \RuntimeException('RealCaptcha encountered an error calling imagettftext() function.');
		}
		// Output the image to the browser.
		header('Content-Type: image/jpeg');
		imagejpeg($image);
		imagedestroy($image);
		$_SESSION['security_code'] = $this->code;
	}

	//-- Internal Methods --------------------

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
	 * @param array $options
	 *
	 * @return array
	 */
	protected function mergeOptions(array $options=null) {
		$defaults = require self::PATH_DEFAULTS;
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
	 * @param integer[] $colour
	 *
	 * @return integer
	 */
	protected function createColour($image, array $colour) {
		return imagecolorallocate($image, $colour['red'], $colour['green'], $colour['blue']);
	}

}
