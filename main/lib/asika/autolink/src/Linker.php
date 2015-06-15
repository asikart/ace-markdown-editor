<?php
/**
 * Part of php-autolink project. 
 *
 * @copyright  Copyright (C) 2015 {ORGANIZATION}. All rights reserved.
 * @license    GNU General Public License version 2 or later;
 */

namespace Asika\Autolink;

/**
 * The Linker class.
 *
 * @method  static  string  convert()       convert($url, $sttribs = array())
 * @method  static  string  convertEmail()  convertEmail($url, $sttribs = array())
 * @method  static  string  link()          link($url, $sttribs = array())
 *
 * @since  1.0
 */
class Linker
{
	/**
	 * Property instance.
	 *
	 * @var  Autolink
	 */
	protected static $instance;
	
	/**
	 * getInstance
	 *
	 * @param array $options
	 * @param array $schemes
	 *
	 * @return  Autolink
	 */
	public static function getInstance($options = array(), $schemes = array())
	{
		if (static::$instance instanceof Autolink)
		{
			return static::$instance;
		}

		return static::$instance = new Autolink($options, $schemes);
	}

	/**
	 * Method to set property instance
	 *
	 * @param   Autolink $instance
	 *
	 * @return  void
	 */
	public static function setInstance($instance)
	{
		static::$instance = $instance;
	}

	/**
	 * __callStatic
	 *
	 * @param string $name
	 * @param array  $args
	 *
	 * @return  mixed
	 */
	public static function __callStatic($name, $args)
	{
		$instance = static::getInstance();

		if (is_callable(array($instance, $name)))
		{
			return call_user_func_array(array($instance, $name), $args);
		}

		throw new \BadMethodCallException(sprintf('Method: %s::%s not exists.', 'Autolink', $name));
	}
}
