<?php
/**
 * Part of joomla34 project. 
 *
 * @copyright  Copyright (C) 2015 {ORGANIZATION}. All rights reserved.
 * @license    GNU General Public License version 2 or later;
 */

namespace Akmarkdown\Uploader;

use Joomla\Registry\Registry;

/**
 * The ImageUploader class.
 * 
 * @since  {DEPLOY_VERSION}
 */
class ImageUploader
{
	/**
	 * upload
	 *
	 * @param  \JInput  $input
	 *
	 * @return  array
	 * @throws \Exception
	 */
	public static function upload(\JInput $input)
	{
		$editorPlugin = \JPluginHelper::getPlugin('editors', 'akmarkdown');

		if (!$editorPlugin)
		{
			throw new \Exception('Editor Akmarkdown not exists');
		}

		$params = new Registry($editorPlugin->params);

		$files = $input->files;
		$field = $input->get('field', 'file');
		$type  = $input->get('type', 'post');

		$allows = $params->get('Upload_AllowExtension', '');
		$allows = array_map('strtolower', array_map('trim', explode(',', $allows)));

		$file = $files->getVar($field);
		$src  = $file['tmp_name'];
		$name = $file['name'];
		$tmp = new \SplFileInfo(JPATH_ROOT . '/tmp/ak-upload/' . $name);

		if (empty($file['tmp_name']))
		{
			throw new \Exception('File not upload');
		}

		$ext = pathinfo($name, PATHINFO_EXTENSION);

		if (!in_array($ext, $allows))
		{
			throw new \Exception('File extension now allowed.');
		}

		// Move file to tmp
		if (!is_dir($tmp->getPath()))
		{
			\JFolder::create($tmp->getPath());
		}

		if (is_file($tmp->getPathname()))
		{
			\JFile::delete($tmp->getPathname());
		}

		\JFile::upload($src, $tmp->getPathname());

		$src  = $tmp;
		$dest = static::getDest($name, $params->get('Upload_S3_Subfolder', 'ak-upload'));

		$s3 = new \S3(
			$params->get('Upload_S3_Key'),
			$params->get('Upload_S3_SecretKey')
		);

		$bucket = $params->get('Upload_S3_Bucket');

		$result = $s3::putObject(\S3::inputFile($src->getPathname(), false), $bucket, $dest, \S3::ACL_PUBLIC_READ);

		if (is_file($tmp->getPathname()))
		{
			\JFile::delete($tmp->getPathname());
		}

		if (!$result)
		{
			throw new \Exception('Upload fail.');
		}

		$return = array();

		$return['filename'] = 'https://' . $bucket . '.s3.amazonaws.com/' . $dest;
		$return['file'] = 'https://' . $bucket . '.s3.amazonaws.com/' . $dest;

		return $return;
	}

	/**
	 * getDest
	 *
	 * @param string $name
	 * @param string $subfolder
	 *
	 * @return  string
	 */
	protected static function getDest($name, $subfolder = 'ak-upload')
	{
		$user = \JFactory::getUser();

		$user->username = $user->username ? : 'guest';

		$date = new \JDate('now');

		$year  = $date->year;
		$month = $date->month;
		$day   = $date->day;

		$ext = pathinfo($name, PATHINFO_EXTENSION);

		return sprintf('%s/%s/%s/%s/%s/%s.%s', $subfolder, $user->username, $year, $month, $day, uniqid(\JFactory::getConfig()->get('secret')), $ext);
	}
}
