<?php

use Composer\Autoload\ClassLoader;

$file = __DIR__.'/../vendor/autoload.php';
if (!file_exists($file)) {
    throw new RuntimeException('Install dependencies to run test suite.');
}

/**
 * @var ClassLoader $loader
 */
$loader = require $file;
$loader->add('TreeHouse\Slugifier\Tests', __DIR__ . '/TreeHouse/Slugifier/Tests');
