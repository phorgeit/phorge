#!/usr/bin/env php
<?php

if (function_exists('pcntl_async_signals')) {
  pcntl_async_signals(true);
} else {
  declare(ticks = 1);
}

$root = dirname(dirname(dirname(__FILE__)));
require_once $root.'/scripts/__init_script__.php';

$overseer = new PhutilDaemonOverseer($argv);

$bootloader = PhutilBootloader::getInstance();
foreach ($bootloader->getAllLibraries() as $library) {
  $overseer->addLibrary(phutil_get_library_root($library));
}

$overseer->run();
