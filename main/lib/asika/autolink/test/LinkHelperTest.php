<?php
/**
 * Part of php-autolink project. 
 *
 * @copyright  Copyright (C) 2015 {ORGANIZATION}. All rights reserved.
 * @license    GNU General Public License version 2 or later;
 */

/**
 * The LinkHelperTest class.
 * 
 * @since  1.0
 */
class LinkHelperTest extends \Windwalker\Test\TestCase\AbstractBaseTestCase
{
	/**
	 * urlProvider
	 *
	 * @return  array
	 */
	public function urlProvider()
	{
		return array(
			array(
				'http://www.projectup.net/blog/index.php?option=com_content&view=article&id=15726:-agile-&catid=8:pmp-pm&Itemid=18',
				'http://www.projectup.net/....../index.php?optio......',
				15,
				6
			),
			array(
				'http://campus.asukademy.com/learning/job/84-find-internship-opportunity-through-platform.html',
				'http://campus.asukademy.com/....../84-find-interns......',
				15,
				6
			),
			array(
				'http://user:pass@campus.asukademy.com:8888/learning/job/84-find-internship-opportunity-through-platform.html',
				'http://user:pass@campus.asukademy.com:8888/....../84-find-interns......',
				15,
				6
			),
			array(
				'http://campus.asukademy.com/learning/job/84-find-internship-opportunity-through-platform.html',
				'http://campus.asukademy.com/.../84-fi...',
				5,
				3
			)
		);
	}

	/**
	 * testShorten
	 *
	 * @param $url
	 * @param $expect
	 * @param $limit
	 * @param $dots
	 *
	 * @dataProvider  urlProvider
	 *
	 */
	public function testShorten($url, $expect, $limit, $dots)
	{
		$this->assertEquals($expect, \Asika\Autolink\LinkHelper::shorten($url, $limit, $dots));
	}
}
