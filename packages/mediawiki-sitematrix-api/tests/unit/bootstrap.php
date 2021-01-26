<?php

/**
 * @author Addshore
 */

if ( PHP_SAPI !== 'cli' ) {
	die( 'Not an entry point' );
}

if ( !is_readable( __DIR__ . '/../../vendor/autoload.php' ) ) {
	die( 'You need to install this package with Composer before you can run the tests' );
}

$pwd = getcwd();
chdir( __DIR__ . '/../../' );
passthru( 'composer dump-autoload' );
chdir( $pwd );

$autoloader = require_once __DIR__ . '/../../vendor/autoload.php';

$autoloader->addPsr4( 'Mediawiki\\Sitematrix\\Api\\Test\\', __DIR__ );
