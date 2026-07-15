#!/usr/bin/env php
<?php

$root = dirname(dirname(dirname(__FILE__)));
require_once $root.'/scripts/init/init-script.php';

$args = new PhutilArgumentParser($argv);
$args->setTagline(pht('manage edges'));
$args->setSynopsis(pht(<<<EOSYNOPSIS
**edges** __command__ [__options__]
  Explore edges.

EOSYNOPSIS
  ));
$args->parseStandardArguments();

$workflows = id(new PhutilClassMapQuery())
  ->setAncestorClass(PhorgeEdgeManagementWorkflow::class)
  ->execute();
$workflows[] = new PhutilHelpArgumentWorkflow();
$args->parseWorkflows($workflows);
