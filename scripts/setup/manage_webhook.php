#!/usr/bin/env php
<?php

$root = dirname(dirname(dirname(__FILE__)));
require_once $root.'/scripts/init/init-script.php';

$args = new PhutilArgumentParser($argv);
$args->setTagline(pht('manage webhooks'));
$args->setSynopsis(pht(<<<EOSYNOPSIS
**webhook** __command__ [__options__]
    Manage webhooks.

EOSYNOPSIS
  ));
$args->parseStandardArguments();

$workflows = id(new PhutilClassMapQuery())
  ->setAncestorClass(HeraldWebhookManagementWorkflow::class)
  ->execute();
$workflows[] = new PhutilHelpArgumentWorkflow();
$args->parseWorkflows($workflows);
