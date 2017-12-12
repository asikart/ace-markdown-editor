<?php
/**
 * Part of Windwalker project.
 *
 * @copyright  Copyright (C) 2016 LYRASOFT, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

const _JEXEC = 1;
defined('_JEXEC') or die;

include_once __DIR__ . '/library/console.php';

define('BUILD_ROOT', realpath(__DIR__ . '/..'));

/**
 * Class Build
 *
 * @since 1.0
 */
class Build extends \Asika\SimpleConsole\Console
{
	/**
	 * Property name.
	 *
	 * @var  string
	 */
	protected $name = 'plg_akmarkdown';

	/**
	 * Property removes.
	 *
	 * @var  array
	 */
	protected $ignores = array(
		'/.git/*',
		'/bin/*',
		'/update.xml',
		'/README.md',
//		'phpunit.xml.dist',
//		'README.md',
//		'update.xml'
	);

	protected $help = <<<HELP
[Usage] php build.php <version> [-b|--branch=Branch]

[Options]
    h | help      Show help information
	b | branch    (Optinal) Git branch to build if provided,
	              will back to staging after completed.
HELP;


	/**
	 * execute
	 *
	 * @return  void
	 */
	protected function doExecute()
	{
		// Prepare zip name.
		$zipFile = BUILD_ROOT . '/../%s_%s.zip';

		$version = $this->getArgument(0);

		if (!$version)
		{
			throw new \Asika\SimpleConsole\CommandArgsException('Please enter a version.');
		}

		$branch = $this->getOption(array('b', 'branch'));

		if ($branch && $branch !== 'staging')
		{
			$this->exec('git checkout ' . $branch);
		}

		$zipFile = new \SplFileInfo(static::cleanPath(sprintf($zipFile, $this->name, $version)));

		$dir = new \RecursiveIteratorIterator(new RecursiveDirectoryIterator(BUILD_ROOT, FilesystemIterator::SKIP_DOTS));

		// Start ZIP archive
		$zip = new ZipArchive;

		@unlink($zipFile->getPathname());

		$zip->open($zipFile->getPathname(), ZIPARCHIVE::CREATE);

		/** @var \SplFileInfo $file */
		foreach ($dir as $file)
		{
			$file = str_replace(BUILD_ROOT . DIRECTORY_SEPARATOR , '', $file->getPathname());

			if ($this->testIgnore('/' . $file))
			{
				continue;
			}

			$this->out('[Zip file] ' . $file);
			$zip->addFile(str_replace('\\', '/', $file));
		}

		$zip->close();

		if ($branch && $branch !== 'staging')
		{
			$this->exec('git checkout staging');
		}

		$this->out('Zip success to: ' . realpath($zipFile->getPathname()));
	}

	/**
	 * test
	 *
	 * @param string $string
	 *
	 * @return  boolean
	 */
	public function testIgnore($string)
	{
		$match = false;

		// fnmatch() only work for UNIX file path
		$string = str_replace(array('/', '\\'), '/', $string);

		foreach ($this->ignores as $rule)
		{
			// Negative
			if (substr($rule, 0, 1) == '!')
			{
				$rule = substr($rule, 1);

				if (fnmatch($rule, $string))
				{
					$match = false;
				}
			}
			// Normal
			else
			{
				if (fnmatch($rule, $string))
				{
					$match = true;
				}
			}
		}

		return $match;
	}

	/**
	 * cleanPath
	 *
	 * @param string $path
	 * @param string $ds
	 *
	 * @return  string
	 */
	public static function cleanPath($path, $ds = DIRECTORY_SEPARATOR)
	{
		return str_replace(array('/', '\\'), $ds, $path);
	}
}

$build = new Build;

$build->execute();
