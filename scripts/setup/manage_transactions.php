#!/usr/bin/env php
<?php

$root = dirname(dirname(dirname(__FILE__)));
require_once $root.'/scripts/init/init-script.php';

$args = new PhutilArgumentParser($argv);
$args->setTagline(pht('manage transactions'));
$args->setSynopsis(pht(<<<EOSYNOPSIS
**transactions** __command__ [__options__]

EOSYNOPSIS
  ));
$args->parseStandardArguments();

$workflows = id(new PhutilClassMapQuery())
  ->setAncestorClass(PhorgeTransactionManagementWorkflow::class)
  ->execute();
$workflows[] = new PhutilHelpArgumentWorkflow();
$args->parseWorkflows($workflows);
