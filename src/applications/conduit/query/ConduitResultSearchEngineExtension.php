<?php

final class ConduitResultSearchEngineExtension
  extends PhorgeSearchEngineExtension {

  const EXTENSIONKEY = 'conduit';

  public function isExtensionEnabled() {
    return true;
  }

  public function getExtensionOrder() {
    return 1500;
  }

  public function getExtensionName() {
    return pht('Support for ConduitResultInterface');
  }

  public function supportsObject($object) {
    return ($object instanceof PhorgeConduitResultInterface);
  }

  public function getFieldSpecificationsForConduit($object) {
    return $object->getFieldSpecificationsForConduit();
  }

  public function getFieldValuesForConduit($object, $data) {
    return $object->getFieldValuesForConduit();
  }

  public function getSearchAttachments($object) {
    return $object->getConduitSearchAttachments();
  }

}
