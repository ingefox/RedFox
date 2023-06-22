<?php

	define('APPPATH', dirname(__FILE__) . '/');
	define('ENVIRONMENT', 'development');

	chdir(APPPATH);

	require __DIR__ . '/Libraries/Doctrine.php';

	$doctrine = new App\Libraries\Doctrine;
	$em       = $doctrine->em;

	$helperSet = \Doctrine\ORM\Tools\Console\ConsoleRunner::createHelperSet($em);

	\Doctrine\ORM\Tools\Console\ConsoleRunner::run($helperSet);
