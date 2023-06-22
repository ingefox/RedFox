<?php
namespace App\Libraries;

use Doctrine\Common\Proxy\AbstractProxyFactory;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\Setup;

include_once dirname(__DIR__, 2).DIRECTORY_SEPARATOR.'vendor'.DIRECTORY_SEPARATOR.'autoload.php';
include_once dirname(__DIR__, 1).DIRECTORY_SEPARATOR.'Modules'.DIRECTORY_SEPARATOR.'RFCore'.DIRECTORY_SEPARATOR.'Entities'.DIRECTORY_SEPARATOR.'RF_Entity.php';

class Doctrine {

	public $em = null;

	public function __construct()
	{
		// Retrieving all paths leading to entities classes
		$modulePath   = APPPATH . 'Modules'.DIRECTORY_SEPARATOR.'*'.DIRECTORY_SEPARATOR.'Entities';
		$entitiesPath = glob($modulePath, GLOB_ONLYDIR);

		/*
		 * If `$isDevMode` is true caching is done in memory with the ArrayCache. Proxy objects are recreated on every request.
		 * If `$isDevMode` is false, check for Caches in the order APC, Xcache, Memcache (127.0.0.1:11211), Redis (127.0.0.1:6379) unless `$cache` is passed as fourth argument.
		 * If `$isDevMode` is false, set then proxy classes have to be explicitly created through the command line.
		 * If third argument `$proxyDir` is not set, use the systems temporary directory.
		 */
		$isDevMode = false;

		// Generating DB connection configuration array
		$dbParams = [
			'driver'   => 'mysqli',
			'user'     => 'root',
			'password' => '',
			'dbname'   => 'RedFox',
		];

		$proxies_dir = APPPATH . 'Models'.DIRECTORY_SEPARATOR.'Proxies';

		$config = Setup::createAnnotationMetadataConfiguration($entitiesPath, $isDevMode, $proxies_dir);
		$config->setAutoGenerateProxyClasses(AbstractProxyFactory::AUTOGENERATE_ALWAYS);

		try
		{
			$this->em = EntityManager::create($dbParams, $config);
		}
		catch (\Doctrine\ORM\ORMException $e)
		{
			log_message('Doctrine Exception : ', $e);
		}
	}
}
