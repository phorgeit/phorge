#!/usr/bin/env php
<?php

$root = dirname(dirname(dirname(__FILE__)));
require_once $root.'/scripts/__init_script__.php';

PhorgeDaemonManagementWorkflow::requireExtensions();

$args = new PhutilArgumentParser($argv);
$args->setTagline(pht('manage daemons'));
$args->setSynopsis(<<<EOSYNOPSIS
**phd** __command__ [__options__]
    Manage Phorge daemons.

EOSYNOPSIS
  );
$args->parseStandardArguments();

$workflows = id(new PhutilClassMapQuery())
  ->setAncestorClass('PhorgeDaemonManagementWorkflow')
  ->execute();
$workflows[] = new PhutilHelpArgumentWorkflow();
$args->parseWorkflows($workflows);
