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

use RealCaptcha\CodeGenerator\CodeGeneratorInterface;
use RealCaptcha\LayerRenderer\LayerRendererInterface;
use RealCaptcha\Util\ColourUtilities;

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
	const TYPE_ALPHANUMERIC = 'Alphanumeric';

	/**
	 * Captcha type that displays a simple mathematical expression (addition, subtraction, multiplication or division)
	 * and requests the user type the correct answer.
	 */
	const TYPE_MATHEMATICAL = 'Mathematical';

	/**
	 * Relative path to the default configuration file.
	 */
	const PATH_DEFAULTS = '../../config/defaults.php';

	/**
	 * Base session key, used on its own or after a namespace prefix is one is set.
	 */
	const SESSION_KEY_BASE_CODE = 'code';

	/**
	 * FQCN pattern for code generators.
	 */
	const CLASS_NAME_FORMAT_CODE_GENERATOR = '\\RealCaptcha\\CodeGenerator\\%sCodeGenerator';

	/**
	 * FQCN pattern for layer renderers.
	 */
	const CLASS_NAME_FORMAT_LAYER_RENDERER = '\\RealCaptcha\\LayerRenderer\\%sLayerRenderer';

	//-- Attributes --------------------

	/**
	 * @var array
	 */
	private $options;

	//-- Constructor --------------------

	/**
	 * @param array|null $options
	 *
	 * @throws \RuntimeException
	 */
	public function __construct(array $options=null) {
		$this->options = $this->mergeOptions($options);
		$this->initSession();
	}

	//-- Public Methods --------------------

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
		$image = $this->prepareImage();
		foreach ($this->getOption('layers') as $layer) {
			$this->renderLayer($image, $layer);
		}
		$this->outputImage($image);
		$this->cleanupImage($image);
	}

	/**
	 * Generate the code for this captcha, and store it in the session.
	 *
	 * @return string
	 *
	 * @throws \InvalidArgumentException
	 */
	public function generateCode() {
		$className = sprintf(self::CLASS_NAME_FORMAT_CODE_GENERATOR, $this->getOption('type'));
		/** @var CodeGeneratorInterface $generator */
		$generator = new $className($this);
		$code = $generator->generateCode();
		$_SESSION[$this->getSessionKey()] = $code;
		return $code;
	}

	/**
	 * Compare the specified code against the code stored in the session, and remove the code from the session as it
	 * should only ever be checked once.
	 *
	 * @param string $code
	 *
	 * @return boolean
	 */
	public function checkCode($code) {
		$sessionKey = $this->getSessionKey();
		if (!isset($_SESSION[$sessionKey])) {
			// No code to compare against, so it can't be correct.
			return false;
		}
		$storedCode = $_SESSION[$sessionKey];
		unset($_SESSION[$sessionKey]);
		return strtolower($code) === strtolower($storedCode['result']);
	}

	//-- Internal Methods --------------------

	/**
	 * Initialise the session, if it needs to be and if it is possible.
	 *
	 * @throws \RuntimeException
	 */
	protected function initSession() {
		/** @noinspection PhpVoidFunctionResultUsedInspection Issue with PhpStorm 6.0 */
		$startSession = false;
		if (is_callable('session_status')) {
			switch (session_status()) {
				case PHP_SESSION_ACTIVE:
					// Do nothing, session is already started.
					break;
				case PHP_SESSION_NONE:
					$startSession = true;
					break;
				case PHP_SESSION_DISABLED:
					throw new \RuntimeException('RealCaptcha requires sessions to be enabled.');
			}
		} elseif (session_id() === '') {
			$startSession = true;
		}
		if ($startSession) {
			session_start();
		}
	}

	/**
	 * Generate the session key based on the configured namespace.
	 *
	 * @return string
	 */
	protected function getSessionKey() {
		$namespace = $this->getOption('namespace');
		return empty($namespace) ? self::SESSION_KEY_BASE_CODE : sprintf('%s.%s', $namespace, self::SESSION_KEY_BASE_CODE);
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
		if (!isset($options['type'])) {
			$options['type'] = self::TYPE_ALPHANUMERIC;
		}
		return array_merge(
			$defaults['base'],
			$defaults[strtolower($options['type'])],
			$options ?: array()
		);
	}

	/**
	 * Prepare the image for drawing.
	 *
	 * @throws \RuntimeException
	 */
	protected function prepareImage() {
		$width = $this->getOption('width');
		$height = $this->getOption('height');
		if (!($image = imagecreate($width, $height))) {
			throw new \RuntimeException('RealCaptcha could not create image');
		}
		$colour = ColourUtilities::createColour($image, $this->getOption('background-colour'));
		imagefilledrectangle($image, 0, 0, $width, $height, $colour);
		return $image;
	}

	/**
	 * Wrapper function to render a single named layer.
	 *
	 * @param resource $image
	 * @param string $layer
	 */
	protected function renderLayer($image, $layer) {
		$className = sprintf(self::CLASS_NAME_FORMAT_LAYER_RENDERER, $layer);
		/** @var LayerRendererInterface $renderer */
		$renderer = new $className($this);
		$renderer->render($image);
	}

	/**
	 * Send the headers and image contents to the browser.
	 *
	 * @param resource $image
	 *
	 * @throws \InvalidArgumentException
	 */
	protected function outputImage($image) {
		$format = strtolower($this->getOption('image-format'));
		header('Cache-Control: no-cache');
		header('Expires: ' . date('r'));
		header('Content-Type: image/' . $format);
		$method = 'image' . $format;
		$method($image);
	}

	/**
	 * Release resources used by the image.
	 *
	 * @param resource $image
	 */
	protected function cleanupImage($image) {
		imagedestroy($image);
	}

}
