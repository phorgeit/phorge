#!/usr/bin/env php
<?php

$root = dirname(dirname(dirname(__FILE__)));
require_once $root.'/scripts/init/init-setup.php';

$args = new PhutilArgumentParser($argv);
$args->setTagline(pht('manage internationalization'));
$args->setSynopsis(<<<EOSYNOPSIS
**i18n** __command__ [__options__]
    Manage translations and internationalization.

EOSYNOPSIS
  );
$args->parseStandardArguments();

$workflows = id(new PhutilClassMapQuery())
  ->setAncestorClass(PhabricatorInternationalizationManagementWorkflow::class)
  ->execute();
$workflows[] = new PhutilHelpArgumentWorkflow();
$args->parseWorkflows($workflows);
