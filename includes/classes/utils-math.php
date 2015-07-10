<?php
/**
 * Math Utilities
 *
 * @since 141111 First documented version.
 * @copyright WebSharks, Inc. <http://www.websharks-inc.com>
 * @license GNU General Public License, version 3
 */
namespace comment_mail // Root namespace.
{
	if(!defined('WPINC')) // MUST have WordPress.
		exit('Do NOT access this file directly: '.basename(__FILE__));

	if(!class_exists('\\'.__NAMESPACE__.'\\utils_math'))
	{
		/**
		 * Math Utilities
		 *
		 * @since 141111 First documented version.
		 */
		class utils_math extends abs_base
		{
			/**
			 * Class constructor.
			 *
			 * @since 141111 First documented version.
			 */
			public function __construct()
			{
				parent::__construct();
			}

			/**
			 * Calculates percentage value.
			 *
			 * @param integer|float $value Amount/value to calculate.
			 *
			 * @param integer|float $of Percentage of what? Defaults to `100`.
			 *    NOTE: This value may NOT be empty. That's not possible to calculate.
			 *
			 * @param integer       $precision Optional. Defaults to `0`; no decimal place.
			 *
			 * @param boolean       $format_string Optional. Defaults to a FALSE value.
			 *    If this is TRUE, a string is returned; and it is formatted (e.g. `[percent]%`).
			 *
			 * @return integer|float Percentage. A float if `$precision` is passed; else an integer (default behavior).
			 *    If `$format_string` is TRUE, the value is always converted to string format (e.g. `[percent]%`).
			 */
			public function percent($value, $of = 100, $precision = 0, $format_string = FALSE)
			{
				if(!is_integer($value) && !is_float($value))
					$value = (integer)$value;

				if(!is_integer($of) && !is_float($of))
					$of = (integer)$of;

				$precision = abs((integer)$precision);

				if($of <> 0) // Cannot divide by `0`.
					$percent = number_format(($value / $of) * 100, $precision, '.', '');
				else $percent = 0; // Default value.

				if($format_string) // Keep string format w/ `%` suffix.
					return $percent.'%'; // e.g. `5%` or `5.5%`.

				return $precision ? (float)$percent : (integer)$percent;
			}
		}
	}
}