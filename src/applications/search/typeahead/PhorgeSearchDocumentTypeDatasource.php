<?php

final class PhorgeSearchDocumentTypeDatasource
  extends PhorgeTypeaheadDatasource {

  public function getBrowseTitle() {
    return pht('Browse Document Types');
  }

  public function getPlaceholderText() {
    return pht('Select a document type...');
  }

  public function getDatasourceApplicationClass() {
    return 'PhorgeSearchApplication';
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
      PhorgeSearchApplicationSearchEngine::getIndexableDocumentTypes(
        $viewer);

    $phid_types = mpull(PhorgePHIDType::getAllTypes(),
      null,
      'getTypeConstant');

    $results = array();
    foreach ($types as $type => $name) {
      $type_object = idx($phid_types, $type);
      if (!$type_object) {
        continue;
      }
      $application_class = $type_object->getPHIDTypeApplicationClass();
      $application = PhorgeApplication::getByClass($application_class);
      $application_name = $application->getName();

      $results[$type] = id(new PhorgeTypeaheadResult())
        ->setPHID($type)
        ->setName($name)
        ->addAttribute($application_name)
        ->setIcon($type_object->getTypeIcon());
    }

    return $results;
  }

}
