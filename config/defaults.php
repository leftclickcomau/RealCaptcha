<?php
/*!
 * This file is a part of RealCaptcha human verification system.
 * Copyright (c) Ben New, Leftclick.com.au
 * See the LICENSE and README files in the main source directory for details.
 */

/**
 * Default settings for RealCaptcha.
 */
return array(

	/**
	 * Base settings always applied.
	 */
	'base' => array(

		/**
		 * Width of the generated image, in pixels.
		 */
		'width' => 200,

		/**
		 * Height of the generated image, in pixels.
		 */
		'height' => 40,

		/**
		 * Background colour for the image.
		 */
		'background-colour' => array(
			'red' => 255,
			'green' => 255,
			'blue' => 255
		),

		/**
		 * Colour for text.
		 */
		'text-colour' => array(
			'red' => 20,
			'green' => 40,
			'blue' => 100
		),

		/**
		 * Colour for noise dots, lines and shapes.
		 */
		'noise-colour' => array(
			'red' => 100,
			'green' => 120,
			'blue' => 180
		),

		'noise-dots' => array(
			'divisor' => 3,
			'min' => 0,
			'max' => 100
		),

		'noise-lines' => array(
			'divisor' => 150,
			'min' => 0,
			'max' => 15
		),

		/**
		 * Font filename, must be a valid TTF file.
		 */
		'font' => 'monofont.ttf',

		/**
		 * Ratio between the height of the image and the font size setting.
		 */
		'font-size-ratio' => 0.75
	),

	/**
	 * Settings for 'alphanumeric' type.
	 */
	'alphanumeric' => array(

		/**
		 * Number of characters to display.
		 */
		'length' => 6,

		/**
		 * List of characters to include in the output.
		 */
		'characters' => '23456789bcdfghjkmnpqrstvwxyzBCDFGHJKMNPQRSTVWXYZ'
	),

	/**
	 * Settings for 'mathematical' type.
	 */
	'mathematical' => array(

		/**
		 * Minimum value of each operand.
		 */
		'operand-min' => 2,

		/**
		 * Maximum value of each operand.
		 */
		'operand-max' => 20,

		/**
		 * Number of decimals to allow in the result.  For example setting 1 will allow "3 / 2" (1.5) but will not
		 * allow "5 / 4" (1.25).  Recurring decimal results (e.g. "1 / 3") are never allowed.
		 */
		'result-decimal-max' => 0
	)
);
