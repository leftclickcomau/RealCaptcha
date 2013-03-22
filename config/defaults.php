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
		 * Namespace for session storage, allows multiple captchas on the same page, not required if only using one
		 * captcha per page.
		 */
		'namespace' => null,

		/**
		 * Width of the generated image, in pixels.
		 */
		'width' => 200,

		/**
		 * Height of the generated image, in pixels.
		 */
		'height' => 60,

		/**
		 * Background colour for the image.
		 */
		'background-colour' => array(
			'red' => 238,
			'green' => 238,
			'blue' => 238
		),

		/**
		 * Sequence of rendering elements, items later in the list will be rendered over the top of preceding items.
		 * Note that items can be used multiple times, or not at all, except the 'code' item, which should be used
		 * exactly once.  Available items: 'code', 'shapes', 'lines', 'dots'.
		 */
		'render-sequence' => array(
			'shapes',
			'dots',
			'code',
			'lines'
		),

		/**
		 * Path settings.
		 */
		'paths' => array(
			/**
			 * Path to the directory containing TTF font files.
			 */
			'font' => __DIR__ . '/../fonts',
		),

		/**
		 * Details for rendering text.
		 */
		'text' => array(
			/**
			 * Text colour.
			 */
			'colour' => array(
				'red' => 0,
				'green' => 0,
				'blue' => 0
			),
			/**
			 * Font filename, must be a valid TTF filename, or an array of TTF filenames, within 'font-path' (without any
			 * filename extension).
			 */
			'font' => array(
				'alanden',
				'bsurp',
				'ehermes',
				'luggerbug',
				'monofont',
				'rascal',
				'scrawl',
				'wavy'
			),
			/**
			 * Ratio between the height of the image and the font size setting.
			 */
			'font-size-ratio' => array(
				'width' => 0.2,
				'height' => 0.5
			),

			/**
			 * Minimum and maximum angle for the text, in degrees.
			 */
			'angle' => array(
				'min' => -15,
				'max' => 15
			)
		),

		/**
		 * Details for rendering dots, lines and shapes as noise.
		 */
		'noise' => array(
			/**
			 * Settings for dots.
			 */
			'dots' => array(
				'divisor' => 3,
				'min' => 0,
				'max' => 100
			),
			/**
			 * Settings for lines.
			 */
			'lines' => array(
				'divisor' => 250,
				'min' => 0,
				'max' => 10
			),
			/**
			 * Settings for shapes (rectangles, ellipses, arcs).
			 */
			'shapes' => array(
				'divisor' => 500,
				'min' => 0,
				'max' => 5,
				'size-divisor' => 3,
				'distribution' => array(
					array(
						'shape' => 'rectangle',
						'weight' => 10
					),
					array(
						'shape' => 'ellipse',
						'weight' => 7
					),
					array(
						'shape' => 'arc',
						'weight' => 3
					)
				)
			),
			/**
			 * Colour for noise shapes.
			 */
			'colour' => array(
				'red' => array( 'min' => 0, 'max' => 255 ),
				'green' => array( 'min' => 0, 'max' => 255 ),
				'blue' => array( 'min' => 0, 'max' => 255 )
			)
		)
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
