<?php

final class PhorgeApplicationEditor
  extends PhorgeApplicationTransactionEditor {

  public function getEditorApplicationClass() {
    return 'PhorgeApplicationsApplication';
  }

  public function getEditorObjectsDescription() {
    return pht('Application');
  }

  protected function supportsSearch() {
    return true;
  }

  public function getTransactionTypes() {
    $types = parent::getTransactionTypes();
    $types[] = PhorgeTransactions::TYPE_VIEW_POLICY;
    $types[] = PhorgeTransactions::TYPE_EDIT_POLICY;

    return $types;
  }

  protected function shouldSendMail(
    PhorgeLiskDAO $object,
    array $xactions) {
    return false;
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
