<?php

abstract class PhorgeSearchResultBucket
  extends Phobject {

  private $viewer;
  private $pageSize;

  final public function setPageSize($page_size) {
    $this->pageSize = $page_size;
    return $this;
  }

  final public function getPageSize() {
    if ($this->pageSize === null) {
      return $this->getDefaultPageSize();
    }

    return $this->pageSize;
  }

  public function setViewer(PhorgeUser $viewer) {
    $this->viewer = $viewer;
    return $this;
  }

  public function getViewer() {
    return $this->viewer;
  }

  protected function getDefaultPageSize() {
    return 1000;
  }

  abstract public function getResultBucketName();
  abstract protected function buildResultGroups(
    PhorgeSavedQuery $query,
    array $objects);

  final public function newResultGroups(
    PhorgeSavedQuery $query,
    array $objects) {
    return $this->buildResultGroups($query, $objects);
  }

  final public function getResultBucketKey() {
    return $this->getPhobjectClassConstant('BUCKETKEY');
  }

  final protected function newGroup() {
    return new PhorgeSearchResultBucketGroup();
  }

}
