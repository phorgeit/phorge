<?php

final class PhorgeSpacesMailEngineExtension
  extends PhorgeMailEngineExtension {

  const EXTENSIONKEY = 'spaces';

  public function supportsObject($object) {
    return ($object instanceof PhorgeSpacesInterface);
  }

  public function newMailStampTemplates($object) {
    return array(
      id(new PhorgePHIDMailStamp())
        ->setKey('space')
        ->setLabel(pht('Space')),
    );
  }

  public function newMailStamps($object, array $xactions) {
    $editor = $this->getEditor();
    $viewer = $this->getViewer();

    if (!PhorgeSpacesNamespaceQuery::getSpacesExist()) {
      return;
    }

    $space_phid = PhorgeSpacesNamespaceQuery::getObjectSpacePHID(
      $object);

    $this->getMailStamp('space')
      ->setValue($space_phid);
  }

}
