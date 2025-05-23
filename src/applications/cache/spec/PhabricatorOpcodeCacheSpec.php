<?php

final class PhabricatorOpcodeCacheSpec extends PhabricatorCacheSpec {

  public static function getActiveCacheSpec() {
    $spec = new PhabricatorOpcodeCacheSpec();

    if (extension_loaded('Zend OPcache')) {
      $spec->initOpcacheSpec();
    } else {
      $spec->initNoneSpec();
    }

    return $spec;
  }

  private function initOpcacheSpec() {
    $this
      ->setName(pht('Zend OPcache'))
      ->setVersion(phpversion('Zend OPcache'));

    if (ini_get('opcache.enable')) {
      $this
        ->setIsEnabled(true)
        ->setClearCacheCallback('opcache_reset');

      $status = opcache_get_status();
      $memory = $status['memory_usage'];

      $mem_used = $memory['used_memory'];
      $mem_free = $memory['free_memory'];
      $mem_junk = $memory['wasted_memory'];
      $this->setUsedMemory($mem_used + $mem_junk);
      $this->setTotalMemory($mem_used + $mem_junk + $mem_free);
      $this->setEntryCount($status['opcache_statistics']['num_cached_keys']);

      $is_dev = PhabricatorEnv::getEnvConfig('phabricator.developer-mode');

      $validate = ini_get('opcache.validate_timestamps');
      $freq = ini_get('opcache.revalidate_freq');
      if ($is_dev && (!$validate || $freq)) {
        $summary = pht(
          'OPcache is not configured properly for development.');

        $message = pht(
          'In development, OPcache should be configured to always reload '.
          'code so nothing needs to be restarted after making changes. To do '.
          'this, enable "%s" and set "%s" to 0.',
          'opcache.validate_timestamps',
          'opcache.revalidate_freq');

        $this
          ->newIssue('extension.opcache.devmode')
          ->setShortName(pht('OPcache Config'))
          ->setName(pht('OPcache Not Configured for Development'))
          ->setSummary($summary)
          ->setMessage($message)
          ->addPHPConfig('opcache.validate_timestamps')
          ->addPHPConfig('opcache.revalidate_freq')
          ->addPhabricatorConfig('phabricator.developer-mode');
      } else if (!$is_dev && $validate) {
        $summary = pht('OPcache is not configured ideally for production.');

        $message = pht(
          'In production, OPcache should be configured to never '.
          'revalidate code. This will slightly improve performance. '.
          'To do this, disable "%s" in your PHP configuration.',
          'opcache.validate_timestamps');

        $this
          ->newIssue('extension.opcache.production')
          ->setShortName(pht('OPcache Config'))
          ->setName(pht('OPcache Not Configured for Production'))
          ->setSummary($summary)
          ->setMessage($message)
          ->addPHPConfig('opcache.validate_timestamps')
          ->addPhabricatorConfig('phabricator.developer-mode');
      }
    } else {
      $this->setIsEnabled(false);

      $summary = pht('Enabling OPcache will dramatically improve performance.');
      $message = pht(
        'The PHP "Zend OPcache" extension is installed, but not enabled in '.
        'your PHP configuration. Enabling it will dramatically improve '.
        'performance. Edit the "%s" setting to enable the extension.',
        'opcache.enable');

      $this->newIssue('extension.opcache.enable')
        ->setShortName(pht('OPcache Disabled'))
        ->setName(pht('Zend OPcache Not Enabled'))
        ->setSummary($summary)
        ->setMessage($message)
        ->addPHPConfig('opcache.enable');
    }
  }

  private function initNoneSpec() {
    $message = pht(
      'Installing the "Zend OPcache" extension will dramatically improve '.
      'performance.');

    $this
      ->newIssue('extension.opcache')
      ->setShortName(pht('OPcache'))
      ->setName(pht('Zend OPcache Not Installed'))
      ->setMessage($message)
      ->addPHPExtension('Zend OPcache');
  }
}
