<?php

final class PhorgePhamePostPHIDType extends PhorgePHIDType {

  const TYPECONST = 'POST';

  public function getTypeName() {
    return pht('Phame Post');
  }

  public function newObject() {
    return new PhamePost();
  }

  public function getPHIDTypeApplicationClass() {
    return 'PhorgePhameApplication';
  }

  protected function buildQueryForObjects(
    PhorgeObjectQuery $query,
    array $phids) {

    return id(new PhamePostQuery())
      ->withPHIDs($phids);
  }

  public function loadHandles(
    PhorgeHandleQuery $query,
    array $handles,
    array $objects) {

    foreach ($handles as $phid => $handle) {
      $post = $objects[$phid];
      $handle->setName($post->getTitle());
      $handle->setFullName(pht('Blog Post: ').$post->getTitle());
      $handle->setURI('/J'.$post->getID());

      if ($post->isArchived()) {
        $handle->setStatus(PhorgeObjectHandle::STATUS_CLOSED);
      }

    }

  }

}
