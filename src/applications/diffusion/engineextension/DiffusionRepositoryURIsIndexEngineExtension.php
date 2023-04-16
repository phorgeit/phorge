<?php

final class DiffusionRepositoryURIsIndexEngineExtension
  extends PhorgeIndexEngineExtension {

  const EXTENSIONKEY = 'diffusion.repositories.uri';

  public function getExtensionName() {
    return pht('Repository URIs');
  }

  public function shouldIndexObject($object) {
    return ($object instanceof PhorgeRepository);
  }

  public function indexObject(
    PhorgeIndexEngine $engine,
    $object) {

    // Reload the repository to pick up URIs, which we need in order to update
    // the URI index.
    $object = id(new PhorgeRepositoryQuery())
      ->setViewer(PhorgeUser::getOmnipotentUser())
      ->withPHIDs(array($object->getPHID()))
      ->needURIs(true)
      ->executeOne();
    if (!$object) {
      return;
    }

    $object->updateURIIndex();
  }

}
