<?php

final class PhorgeUserTransactionEditor
  extends PhorgeApplicationTransactionEditor {

  public function getEditorApplicationClass() {
    return 'PhorgePeopleApplication';
  }

  public function getEditorObjectsDescription() {
    return pht('Users');
  }

  protected function shouldPublishFeedStory(
    PhorgeLiskDAO $object,
    array $xactions) {
    return true;
  }

  protected function getMailTo(PhorgeLiskDAO $object) {
    return array();
  }

  protected function getMailCC(PhorgeLiskDAO $object) {
    return array();
  }

}
