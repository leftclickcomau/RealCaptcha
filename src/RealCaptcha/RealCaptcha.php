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
use RealCaptcha\GraphicsEngine\GraphicsEngineInterface;
use RealCaptcha\LayerRenderer\LayerRendererInterface;
use RealCaptcha\Util\CaptchaUtilities;

/**
 * RealCaptcha provides an easy to implement captcha mechanism for any type of web form.
 *
 * @package RealCaptcha
 */
class RealCaptcha implements OptionsInterface, CaptchaInterface {

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
	 * FQCN pattern for graphics engines.
	 */
	const CLASS_NAME_FORMAT_GRAPHICS_ENGINE = '\\RealCaptcha\\GraphicsEngine\\%sGraphicsEngine';

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
    // Merge options into defaults
    /** @noinspection PhpIncludeInspection */
    $defaults = require sprintf('%s/%s', __DIR__, self::PATH_DEFAULTS);
    if (!isset($options['type'])) {
      $options['type'] = self::TYPE_ALPHANUMERIC;
    }
    $this->options = array_merge_recursive(
      $defaults['base'],
      $defaults[strtolower($options['type'])],
      $options ?: array()
    );

    // Start session if required
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

	//-- OptionsInterface Methods --------------------

	public function getOption($key) {
		$options = $this->options;
		foreach (explode('.', $key) as $parentKey) {
			$options = is_array($options) && isset($options[$parentKey]) ? $options[$parentKey] : null;
		}
		return $options;
	}

	public function setOption($key, $value) {
		$options = $this->options;
		$keys = explode('.', $key);
		$childKey = array_pop($keys);
		foreach ($keys as $parentKey) {
			$options = isset($options[$parentKey]) ? $options[$parentKey] : array();
		}
		$options[$childKey] = $value;
		foreach (array_reverse($keys) as $parentKey) {
			$options = array( $parentKey => $options );
		}
		$this->options = array_merge($this->options, $options);
	}

	//-- CaptchaInterface Methods --------------------

	public function writeImage() {
		$graphicsEngineClassName = sprintf(self::CLASS_NAME_FORMAT_GRAPHICS_ENGINE, $this->getOption('graphics-engine'));
		/** @var GraphicsEngineInterface $graphicsEngine */
		$graphicsEngine = new $graphicsEngineClassName();
		$graphicsEngine->initialise(
			intval($this->getOption('width')),
			intval($this->getOption('height')),
			$this->getOption('background-colour')
		);

		foreach ($this->getOption('layers') as $layer) {
			$layerRendererClassName = sprintf(self::CLASS_NAME_FORMAT_LAYER_RENDERER, $layer);
			/** @var LayerRendererInterface $renderer */
			$renderer = new $layerRendererClassName($this);
			$renderer->render($graphicsEngine, $this);
		}

		$format = strtolower($this->getOption('image-format'));
		header('Cache-Control: no-cache');
		header('Expires: ' . date('r'));
		header('Content-Type: image/' . $format);
		$graphicsEngine->render($format);
		$graphicsEngine->cleanup();
	}

	public function generateCode() {
		$codeGeneratorClassName = sprintf(self::CLASS_NAME_FORMAT_CODE_GENERATOR, $this->getOption('type'));
		/** @var CodeGeneratorInterface $generator */
		$generator = new $codeGeneratorClassName($this);
		$code = $generator->generateCode();
		$_SESSION[$this->getSessionKey()] = $code;
		return $code;
	}

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

 	protected function getSessionKey() {
		$namespace = $this->getOption('namespace');
		return empty($namespace) ? self::SESSION_KEY_BASE_CODE : sprintf('%s.%s', $namespace, self::SESSION_KEY_BASE_CODE);
 	}

}
