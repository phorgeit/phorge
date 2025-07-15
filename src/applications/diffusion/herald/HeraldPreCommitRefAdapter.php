<?php

final class HeraldPreCommitRefAdapter
  extends HeraldPreCommitAdapter 
  implements HarbormasterBuildableAdapterInterface {

  private $buildRequests = array();

  public function getAdapterContentName() {
    return pht('Commit Hook: Branches/Tags/Bookmarks');
  }

  public function getAdapterSortOrder() {
    return 2000;
  }

  /* TM CHANGES */
  public function getAdapterContentDescription() {
    return pht(
      "React to branches and tags being pushed to hosted repositories.\n".
      "Hook rules can block changes, send push summary mail, ".
      "and run build plans.");
  }
  /* TM CHANGES END */

  public function isPreCommitRefAdapter() {
    return true;
  }

  public function getHeraldName() {
    return pht('Push Log (Ref)');
  }


  /* TM CHANGES */
  /* -(  HarbormasterBuildableAdapterInterface  )------------------------------ */


  public function getHarbormasterBuildablePHID() {
    return $this->getObject()->getPHID();
  }

  public function getHarbormasterContainerPHID() {
    return $this->getObject()->getRepository()->getPHID();
  }

  public function getQueuedHarbormasterBuildRequests() {
    return $this->buildRequests;
  }

  public function queueHarbormasterBuildRequest(
    HarbormasterBuildRequest $request) {
    $this->buildRequests[] = $request;
  }

  /* TM CHANGES END */
}
