<?php

final class PhabricatorSecuritySetupCheck extends PhabricatorSetupCheck {

  public function getDefaultGroup() {
    return self::GROUP_OTHER;
  }

  protected function executeChecks() {

    $file_key = 'security.alternate-file-domain';
    $file_domain = PhabricatorEnv::getEnvConfig($file_key);
    if (!$file_domain) {
      $doc_href = PhabricatorEnv::getDoclink('Configuring a File Domain');

      $this->newIssue('security.'.$file_key)
        ->setName(pht('Alternate File Domain Not Configured'))
        ->setSummary(
          pht(
            'Improve security by configuring an alternate file domain.'))
        ->setMessage(
          pht(
            'This software is currently configured to serve user uploads '.
            'directly from the same domain as other content. This is a '.
            'security risk.'.
            "\n\n".
            'Configure a CDN (or alternate file domain) to eliminate this '.
            'risk. Using a CDN will also improve performance. See the '.
            'guide below for instructions.'))
        ->addPhabricatorConfig($file_key)
        ->addLink(
          $doc_href,
          pht('Configuration Guide: Configuring a File Domain'));
    }
  }
}
