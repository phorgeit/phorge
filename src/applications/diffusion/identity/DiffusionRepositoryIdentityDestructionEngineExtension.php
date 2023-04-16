<?php

final class DiffusionRepositoryIdentityDestructionEngineExtension
  extends PhorgeDestructionEngineExtension {

  const EXTENSIONKEY = 'repository-identities';

  public function getExtensionName() {
    return pht('Repository Identities');
  }

  public function didDestroyObject(
    PhorgeDestructionEngine $engine,
    $object) {

    // When users or email addresses are destroyed, queue a task to update
    // any repository identities that are associated with them. See T13444.

    $related_phids = array();
    $email_addresses = array();

    if ($object instanceof PhorgeUser) {
      $related_phids[] = $object->getPHID();
    }

    if ($object instanceof PhorgeUserEmail) {
      $email_addresses[] = $object->getAddress();
    }

    if ($related_phids || $email_addresses) {
      PhorgeWorker::scheduleTask(
        'PhorgeRepositoryIdentityChangeWorker',
        array(
          'relatedPHIDs' => $related_phids,
          'emailAddresses' => $email_addresses,
        ));
    }
  }

}
