<?php

/**
 * Configuration source which reads from a configuration file on disk (a
 * PHP file in the `conf/` directory).
 */
final class PhabricatorConfigFileSource extends PhabricatorConfigProxySource {

  /**
   * @phutil-external-symbol function phorge_read_config_file
   */
  public function __construct($config) {
    $root = dirname(phutil_get_library_root('phorge'));
    require_once $root.'/conf/__init_conf__.php';

    $dictionary = phorge_read_config_file($config);
    $dictionary['phorge.env'] = $config;

    $this->setSource(new PhabricatorConfigDictionarySource($dictionary));
  }

}
