<?php

defined( 'APP_PATH' ) || define( 'APP_PATH', realpath( '.' ) );

return new \Phalcon\Config( array(
	'database'    => array(
		'adapter'  => 'Mysql',
		'host'     => 'localhost',
		'username' => 'soa',
		'password' => 'a39d8591483b67fb637bb445f9f4f059',
		'dbname'   => 'j4f',
		'charset'  => 'utf8',
	),
	'application' => array(
		'controllersDir' => APP_PATH . '/app/controllers/',
		'modelsDir'      => APP_PATH . '/app/models/',
		'migrationsDir'  => APP_PATH . '/app/migrations/',
		'viewsDir'       => APP_PATH . '/app/views/',
		'pluginsDir'     => APP_PATH . '/app/plugins/',
		'libraryDir'     => APP_PATH . '/app/library/',
		'cacheDir'       => APP_PATH . '/app/cache/',
		'helpersDir'     => APP_PATH . '/app/helpers/',
		'baseUri'        => '/j4f/',
	)
) );
