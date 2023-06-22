<?php

namespace Config;

use App\Libraries\Doctrine;
use CodeIgniter\Config\BaseService;
use Doctrine\ORM\EntityManager;

/**
 * Services Configuration file.
 *
 * Services are simply other classes/libraries that the system uses
 * to do its job. This is used by CodeIgniter to allow the core of the
 * framework to be swapped out easily without affecting the usage within
 * the rest of your application.
 *
 * This file holds any application-specific services, or service overrides
 * that you might need. An example has been included with the general
 * method format you should use for your service methods. For more examples,
 * see the core Services file at system/Config/Services.php.
 */
class Services extends BaseService
{
	// public static function example($getShared = true)
	// {
	//     if ($getShared)
	//     {
	//         return static::getSharedInstance('example');
	//     }
	//
	//     return new \CodeIgniter\Example();
	// }

    //DOCTRINE SERVICE CLASS
    public static function doctrine($getShared = false): ?EntityManager
    {
        if (! $getShared)
        {
            // INITIATE
            $doctrine = new Doctrine;
            // SHORTCUT ENTITY MANAGER
            // RETURN ENTITY MANAGER
            return $doctrine->em;
        }
        return static::getSharedInstance('doctrine');
    }
}
