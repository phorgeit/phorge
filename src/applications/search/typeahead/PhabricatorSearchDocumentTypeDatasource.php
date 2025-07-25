<?php

final class PhabricatorSearchDocumentTypeDatasource
  extends PhabricatorTypeaheadDatasource {

  public function getBrowseTitle() {
    return pht('Browse Item Types');
  }

  public function getPlaceholderText() {
    return pht('Select an item type...');
  }

  public function getDatasourceApplicationClass() {
    return PhabricatorSearchApplication::class;
  }

  public function loadResults() {
    $results = $this->buildResults();
    return $this->filterResultsAgainstTokens($results);
  }

  public function renderTokens(array $values) {
    return $this->renderTokensFromResults($this->buildResults(), $values);
  }

  private function buildResults() {
    $viewer = $this->getViewer();
    $types =
      PhabricatorSearchApplicationSearchEngine::getIndexableDocumentTypes(
        $viewer);

    $phid_types = mpull(PhabricatorPHIDType::getAllTypes(),
      null,
      'getTypeConstant');

    $results = array();
    foreach ($types as $type => $name) {
      $type_object = idx($phid_types, $type);
      if (!$type_object) {
        continue;
      }
      $application_class = $type_object->getPHIDTypeApplicationClass();
      $application = PhabricatorApplication::getByClass($application_class);
      $application_name = $application->getName();

      $results[$type] = id(new PhabricatorTypeaheadResult())
        ->setPHID($type)
        ->setName($name)
        ->addAttribute($application_name)
        ->setIcon($type_object->getTypeIcon());
    }

    return $results;
  }

}
