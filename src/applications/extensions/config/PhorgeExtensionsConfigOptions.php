<?php

final class PhorgeExtensionsConfigOptions
extends PhabricatorApplicationConfigOptions {


  public function getName() {
    return pht('Extensions');
  }

  public function getDescription() {
    return pht('Managing and installing extensions');
  }

  public function getGroup() {
    return 'core';
  }

  public function getOptions() {
    $options = array();


    $options[] = $this->newOption(
      'extensions.install-dir',
      'string', // TODO path?
      null)
      ->setLocked(true)
      ->setDescription(pht('Location to download and install extensions to.'));

    $options[] = $this->newOption(
      'extensions.extension-stores',
      'wild',
      null)
      ->setLocked(true)
      ->setDescription(pht('Allowed Extension Stores to use.'));

    return $options;
  }

}
