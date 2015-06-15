<?php
/**
 * Part of php-autolink project. 
 *
 * @copyright  Copyright (C) 2015 {ORGANIZATION}. All rights reserved.
 * @license    GNU General Public License version 2 or later;
 */

namespace Asika\Autolink;

/**
 * The LinkHelper class.
 * 
 * @since  1.0
 */
class LinkHelper
{
	/**
	 * Property defaultParsed.
	 *
	 * @var  array
	 */
	protected static $defaultParsed = array(
		'scheme' => null,
		'user' => null,
		'pass' => null,
		'host' => null,
		'port' => null,
		'path' => null,
		'query' => null,
		'fragment' => null
	);

	public static function shorten($url, $lastPartLimit = 15, $dots = 6)
	{
		$parsed = array_merge(static::$defaultParsed, parse_url($url));

		// @link  http://php.net/manual/en/function.parse-url.php#106731
		$scheme   = isset($parsed['scheme']) ? $parsed['scheme'] . '://' : '';
		$host     = isset($parsed['host']) ? $parsed['host'] : '';
		$port     = isset($parsed['port']) ? ':' . $parsed['port'] : '';
		$user     = isset($parsed['user']) ? $parsed['user'] : '';
		$pass     = isset($parsed['pass']) ? ':' . $parsed['pass']  : '';
		$pass     = ($user || $pass) ? "$pass@" : '';
		$path     = isset($parsed['path']) ? $parsed['path'] : '';
		$query    = isset($parsed['query']) ? '?' . $parsed['query'] : '';
		$fragment = isset($parsed['fragment']) ? '#' . $parsed['fragment'] : '';

		$first = $scheme . $user . $pass . $host . $port . '/';

		$last = $path . $query . $fragment;

		if (!$last)
		{
			return $first;
		}

		if (strlen($last) <= $lastPartLimit)
		{
			return $first . $last;
		}

		$last = explode('/', $last);
		$last = array_pop($last);

		if (strlen($last) > $lastPartLimit)
		{
			$last = '/' . substr($last, 0, $lastPartLimit) . str_repeat('.', $dots);
		}

		return $first . str_repeat('.', $dots) . $last;
	}
}
