<?php

final class PhorgeProjectsSearchEngineExtension
  extends PhorgeSearchEngineExtension {

  const EXTENSIONKEY = 'projects';

  public function isExtensionEnabled() {
    return PhorgeApplication::isClassInstalled(
      'PhorgeProjectApplication');
  }

  public function getExtensionName() {
    return pht('Support for Projects');
  }

  public function getExtensionOrder() {
    return 3000;
  }

  public function supportsObject($object) {
    return ($object instanceof PhorgeProjectInterface);
  }

  public function applyConstraintsToQuery(
    $object,
    $query,
    PhorgeSavedQuery $saved,
    array $map) {

    if (!empty($map['projectPHIDs'])) {
      $query->withEdgeLogicConstraints(
        PhorgeProjectObjectHasProjectEdgeType::EDGECONST,
        $map['projectPHIDs']);
    }
  }

  public function getSearchFields($object) {
    $fields = array();

    $fields[] = id(new PhorgeProjectSearchField())
      ->setKey('projectPHIDs')
      ->setConduitKey('projects')
      ->setAliases(array('project', 'projects', 'tag', 'tags'))
      ->setLabel(pht('Tags'))
      ->setDescription(
        pht('Search for objects tagged with given projects.'));

    return $fields;
  }

  public function getSearchAttachments($object) {
    return array(
      id(new PhorgeProjectsSearchEngineAttachment())
        ->setAttachmentKey('projects'),
    );
  }


}
