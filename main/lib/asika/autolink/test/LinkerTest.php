<?php
/**
 * Part of php-autolink project. 
 *
 * @copyright  Copyright (C) 2015 {ORGANIZATION}. All rights reserved.
 * @license    GNU General Public License version 2 or later;
 */

use Asika\Autolink\Linker;

/**
 * The LinkerTest class.
 * 
 * @since  1.0
 */
class LinkerTest extends \Windwalker\Test\TestCase\AbstractBaseTestCase
{
	/**
	 * testFacade
	 *
	 * @return  void
	 */
	public function testFacade()
	{
		$url = 'http://google.com';

		$this->assertEquals(sprintf('<a href="%s">%s</a>', $url, $url), Linker::convert($url));
		$this->assertEquals(sprintf('<a href="%s">%s</a>', $url, $url), Linker::link($url));

		$this->assertInstanceOf('Asika\Autolink\Autolink', Linker::getInstance());
	}
}
