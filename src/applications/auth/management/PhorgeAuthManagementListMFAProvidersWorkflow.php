<?php

final class PhorgeAuthManagementListMFAProvidersWorkflow
  extends PhorgeAuthManagementWorkflow {

  protected function didConstruct() {
    $this
      ->setName('list-mfa-providers')
      ->setExamples('**list-mfa-providerrs**')
      ->setSynopsis(
        pht(
          'List available multi-factor authentication providers.'))
      ->setArguments(array());
  }

  public function execute(PhutilArgumentParser $args) {
    $viewer = $this->getViewer();

    $providers = id(new PhorgeAuthFactorProviderQuery())
      ->setViewer($viewer)
      ->execute();

    foreach ($providers as $provider) {
      echo tsprintf(
        "%s\t%s\n",
        $provider->getPHID(),
        $provider->getDisplayName());
    }

    return 0;
  }

}
