<?php

final class PhabricatorCoreCreateTransaction
  extends PhabricatorCoreTransactionType {

  const TRANSACTIONTYPE = 'core:create';

  public function generateOldValue($object) {
    return null;
  }

  public function getTitle() {
    $editor = $this->getObject()->getApplicationTransactionEditor();

    $author = $this->renderAuthor();
    $object = $this->renderObject();

    return $editor->getCreateObjectTitle($author, $object);
  }

  public function getTitleForFeed() {
    $editor = $this->getObject()->getApplicationTransactionEditor();

    $author = $this->renderAuthor();
    $object = $this->renderObject();

    return $editor->getCreateObjectTitleForFeed($author, $object);
  }

  public function getActionStrength() {
    // The creation feed is supposed to be "more important" than other things.
    // So a Task is first created and then closed, and not vice-versa.
    // The default null was causing weirdnesses in Maniphest and Phriction.
    // See ManiphestTaskTitleTransaction#getActionStrength()
    // See PhrictionDocumentTitleTransaction#getActionStrength()
    return 140;
  }

}
