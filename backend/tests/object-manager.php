<?php

namespace XpTracker\Tests;

use XpTracker\Kernel;

require __DIR__ . '/bootstrap.php';

$kernel = new Kernel($_SERVER['APP_ENV'], (bool) $_SERVER['APP_DEBUG']);
$kernel->boot();
return $kernel->getContainer()->get('doctrine')->getManager();
