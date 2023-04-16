<?php

abstract class PhorgePackagesEditor
  extends PhorgeApplicationTransactionEditor {

  public function getEditorApplicationClass() {
    return 'PhorgePackagesApplication';
  }

  protected function supportsSearch() {
    return true;
  }

  protected function shouldPublishFeedStory(
    PhorgeLiskDAO $object,
    array $xactions) {
    return true;
  }

}
