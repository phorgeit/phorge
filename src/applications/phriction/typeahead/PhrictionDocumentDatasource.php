<?php

final class PhrictionDocumentDatasource
  extends PhorgeTypeaheadDatasource {

  public function getBrowseTitle() {
    return pht('Browse Documents');
  }

  public function getPlaceholderText() {
    return pht('Type a document name...');
  }

  public function getDatasourceApplicationClass() {
    return 'PhorgePhrictionApplication';
  }

  public function loadResults() {
    $viewer = $this->getViewer();

    $app_type = pht('Wiki Document');
    $mid_dot = "\xC2\xB7";

    $query = id(new PhrictionDocumentQuery())
      ->setViewer($viewer)
      ->needContent(true);

    $this->applyFerretConstraints(
      $query,
      id(new PhrictionDocument())->newFerretEngine(),
      'title',
      $this->getRawQuery());

    $documents = $query->execute();

    $results = array();
    foreach ($documents as $document) {
      $content = $document->getContent();

      if ($document->isActive()) {
        $closed = null;
      } else {
        $closed = $document->getStatusDisplayName();
      }

      $slug = $document->getSlug();
      $title = $content->getTitle();

      // For some time the search result was
      // just mentioning the document slug.
      // Now, it also mentions the application type.
      // Example: "Wiki Document - /foo/bar"
      $display_type = sprintf(
        '%s %s %s',
        $app_type,
        $mid_dot,
        $slug);

      $sprite = 'phorge-search-icon phui-font-fa phui-icon-view fa-book';
      $autocomplete = '[[ '.$slug.' ]]';

      $result = id(new PhorgeTypeaheadResult())
        ->setName($title)
        ->setDisplayName($title)
        ->setURI($document->getURI())
        ->setPHID($document->getPHID())
        ->setDisplayType($display_type)
        ->setPriorityType('wiki')
        ->setImageSprite($sprite)
        ->setAutocomplete($autocomplete)
        ->setClosed($closed);

      $results[] = $result;
    }

    return $results;
  }

}
