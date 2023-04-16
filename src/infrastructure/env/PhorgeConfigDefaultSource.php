<?php

/**
 * Configuration source which reads from defaults defined in the authoritative
 * configuration definitions.
 */
final class PhorgeConfigDefaultSource
  extends PhorgeConfigProxySource {

  public function __construct() {
    $options = PhorgeApplicationConfigOptions::loadAllOptions();
    $options = mpull($options, 'getDefault');
    $this->setSource(new PhorgeConfigDictionarySource($options));
  }

  public function loadExternalOptions() {
    $options = PhorgeApplicationConfigOptions::loadAllOptions(true);
    $options = mpull($options, 'getDefault');
    $this->setKeys($options);
  }

}
