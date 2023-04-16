#!/usr/bin/env php
<?php

$root = dirname(dirname(dirname(dirname(__FILE__))));
require_once $root.'/scripts/init/init-aphlict.php';

PhorgeAphlictManagementWorkflow::requireExtensions();

$args = new PhutilArgumentParser($argv);
$args->setTagline(pht('manage Aphlict notification server'));
$args->setSynopsis(<<<EOSYNOPSIS
**aphlict** __command__ [__options__]
    Manage the Aphlict server.

EOSYNOPSIS
  );
$args->parseStandardArguments();

$workflows = id(new PhutilClassMapQuery())
  ->setAncestorClass('PhorgeAphlictManagementWorkflow')
  ->execute();
$workflows[] = new PhutilHelpArgumentWorkflow();
$args->parseWorkflows($workflows);
