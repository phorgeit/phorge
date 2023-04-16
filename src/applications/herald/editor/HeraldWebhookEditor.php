<?php

final class HeraldWebhookEditor
  extends PhorgeApplicationTransactionEditor {

  public function getEditorApplicationClass() {
    return 'PhorgeHeraldApplication';
  }

  public function getEditorObjectsDescription() {
    return pht('Webhooks');
  }

  public function getCreateObjectTitle($author, $object) {
    return pht('%s created this webhook.', $author);
  }

  public function getCreateObjectTitleForFeed($author, $object) {
    return pht('%s created %s.', $author, $object);
  }

  public function getTransactionTypes() {
    $types = parent::getTransactionTypes();

    $types[] = PhorgeTransactions::TYPE_VIEW_POLICY;
    $types[] = PhorgeTransactions::TYPE_EDIT_POLICY;

    return $types;
  }

}
