<?php

final class PhorgeAuthPasswordEditor
  extends PhorgeApplicationTransactionEditor {

  private $oldHasher;

  public function setOldHasher(PhorgePasswordHasher $old_hasher) {
    $this->oldHasher = $old_hasher;
    return $this;
  }

  public function getOldHasher() {
    return $this->oldHasher;
  }

  public function getEditorApplicationClass() {
    return 'PhorgeAuthApplication';
  }

  public function getEditorObjectsDescription() {
    return pht('Passwords');
  }

  public function getCreateObjectTitle($author, $object) {
    return pht('%s created this password.', $author);
  }

  public function getCreateObjectTitleForFeed($author, $object) {
    return pht('%s created %s.', $author, $object);
  }

}
