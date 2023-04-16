<?php

final class PhorgeSpacesSearchEngineExtension
  extends PhorgeSearchEngineExtension {

  const EXTENSIONKEY = 'spaces';

  public function isExtensionEnabled() {
    return PhorgeApplication::isClassInstalled(
      'PhorgeSpacesApplication');
  }

  public function getExtensionName() {
    return pht('Support for Spaces');
  }

  public function getExtensionOrder() {
    return 4000;
  }

  public function supportsObject($object) {
    return ($object instanceof PhorgeSpacesInterface);
  }

  public function getSearchFields($object) {
    $fields = array();

    if (PhorgeSpacesNamespaceQuery::getSpacesExist()) {
      $fields[] = id(new PhorgeSpacesSearchField())
        ->setKey('spacePHIDs')
        ->setConduitKey('spaces')
        ->setAliases(array('space', 'spaces'))
        ->setLabel(pht('Spaces'))
        ->setDescription(
          pht('Search for objects in certain spaces.'));
    }

    return $fields;
  }

  public function applyConstraintsToQuery(
    $object,
    $query,
    PhorgeSavedQuery $saved,
    array $map) {

    if (!empty($map['spacePHIDs'])) {
      $query->withSpacePHIDs($map['spacePHIDs']);
    } else {
      // If the user doesn't search for objects in specific spaces, we
      // default to "all active spaces you have permission to view".
      $query->withSpaceIsArchived(false);
    }
  }

  public function getFieldSpecificationsForConduit($object) {
    return array(
      id(new PhorgeConduitSearchFieldSpecification())
        ->setKey('spacePHID')
        ->setType('phid?')
        ->setDescription(
          pht('PHID of the policy space this object is part of.')),
    );
  }

  public function getFieldValuesForConduit($object, $data) {
    return array(
      'spacePHID' => $object->getSpacePHID(),
    );
  }

}
