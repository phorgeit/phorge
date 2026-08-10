<?php

final class PhabricatorMentionableRelationshipSource
  extends PhabricatorObjectRelationshipSource {

  public function isEnabledForObject($object) {
    return true;
  }

  public function getResultPHIDTypes() {
    static $mentionable_types = null;
    if ($mentionable_types === null) {
      $types = PhabricatorPHIDType::getAllTypes();

      $mentionable_types = array();
      foreach ($types as $type) {
        $object = $type->newObject();
        if ($object instanceof PhabricatorMentionableInterface) {
          $mentionable_types[] = $type->getTypeConstant();
        }
      }
    }

    return $mentionable_types;
  }

}
