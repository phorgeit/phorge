<?php

final class PhameBlogDatasource
  extends PhorgeTypeaheadDatasource {

  public function getBrowseTitle() {
    return pht('Browse Blogs');
  }

  public function getPlaceholderText() {
    return pht('Type a blog name...');
  }

  public function getDatasourceApplicationClass() {
    return 'PhorgePhameApplication';
  }

  public function loadResults() {
    $viewer = $this->getViewer();

    $blogs = id(new PhameBlogQuery())
      ->setViewer($viewer)
      ->needProfileImage(true)
      ->requireCapabilities(
        array(
          PhorgePolicyCapability::CAN_VIEW,
          PhorgePolicyCapability::CAN_EDIT,
        ))
      ->execute();

    $results = array();
    foreach ($blogs as $blog) {
      $closed = null;

      $status = $blog->getStatus();
      if ($status === PhorgeBadgesBadge::STATUS_ARCHIVED) {
        $closed = pht('Archived');
      }

      $results[] = id(new PhorgeTypeaheadResult())
        ->setName($blog->getName())
        ->setClosed($closed)
        ->addAttribute(pht('Phame Blog'))
        ->setImageURI($blog->getProfileImageURI())
        ->setPHID($blog->getPHID());
    }

    $results = $this->filterResultsAgainstTokens($results);

    return $results;
  }

}
