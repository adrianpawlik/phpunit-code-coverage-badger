#!/usr/bin/env php
<?php

require __DIR__ . '/vendor/autoload.php';

use Symfony\Component\Console\Application;
use AdrianPawlik\PHPUnitCodeCoverageBadger\Commands\CreateBadgeCommand;
use AdrianPawlik\PHPUnitCodeCoverageBadger\Commands\StoreBadgeCommand;

$application = new Application();
$application->add(new CreateBadgeCommand());
$application->add(new StoreBadgeCommand());

$application->run();
