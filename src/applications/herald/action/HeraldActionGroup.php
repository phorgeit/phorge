<?php

abstract class HeraldActionGroup extends HeraldGroup {

  final public function getGroupKey() {
    return $this->getPhobjectClassConstant('ACTIONGROUPKEY');
  }

  final public static function getAllActionGroups() {
    return id(new PhutilClassMapQuery())
      ->setAncestorClass(self::class)
      ->setUniqueMethod('getGroupKey')
      ->setSortMethod('getSortKey')
      ->execute();
  }
}
