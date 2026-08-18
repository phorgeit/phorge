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

  public function getApplicationClassName() {
    return PhorgeExtensionsApplication::class;
  }

  public function getOptions() {
    $options = array();

    $default_install_dir = Filesystem::resolvePath(
      '../../managed-extensions/',
      phutil_get_library_root('phorge'));

    $options[] = $this->newOption(
      'extensions.install-dir',
      'string',
      $default_install_dir)
      ->setLocked(true)
      ->setDescription(pht('Location to download and install extensions to.'));

    $default_extension_store = array(
      array(
        'name' => 'Phorge',
        'uri' => 'https://extensions.phorge.it/',
      ),
    );

    $options[] = $this->newOption(
      'extensions.extension-stores',
      'wild',
      $default_extension_store)
      ->setLocked(true)
      ->setDescription(pht('Allowed Extension Stores to use.'));

    return $options;
  }

}
