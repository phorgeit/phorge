#!/usr/bin/env php
<?php

$root = dirname(dirname(dirname(__FILE__)));
require_once $root.'/scripts/init/init-setup.php';

$args = new PhutilArgumentParser($argv);
$args->setTagline(pht('manage configuration'));
$args->setSynopsis(<<<EOSYNOPSIS
**config** __command__ [__options__]
    Manage Phorge configuration.

EOSYNOPSIS
  );
$args->parseStandardArguments();

$workflows = id(new PhutilClassMapQuery())
  ->setAncestorClass('PhorgeConfigManagementWorkflow')
  ->execute();
$workflows[] = new PhutilHelpArgumentWorkflow();
$args->parseWorkflows($workflows);
