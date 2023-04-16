<?php

final class PhorgeCommentEditEngineExtension
  extends PhorgeEditEngineExtension {

  const EXTENSIONKEY = 'transactions.comment';
  const EDITKEY = 'comment';

  public function getExtensionPriority() {
    return 9000;
  }

  public function isExtensionEnabled() {
    return true;
  }

  public function getExtensionName() {
    return pht('Comments');
  }

  public function supportsObject(
    PhorgeEditEngine $engine,
    PhorgeApplicationTransactionInterface $object) {

    $xaction = $object->getApplicationTransactionTemplate();
    $comment = $xaction->getApplicationTransactionCommentObject();

    return (bool)$comment;
  }

  public function newBulkEditGroups(PhorgeEditEngine $engine) {
    return array(
      id(new PhorgeBulkEditGroup())
        ->setKey('comments')
        ->setLabel(pht('Comments')),
    );
  }

  public function buildCustomEditFields(
    PhorgeEditEngine $engine,
    PhorgeApplicationTransactionInterface $object) {

    $comment_type = PhorgeTransactions::TYPE_COMMENT;

    // Comments have a lot of special behavior which doesn't always check
    // this flag, but we set it for consistency.
    $is_interact = true;

    $comment_field = id(new PhorgeCommentEditField())
      ->setKey(self::EDITKEY)
      ->setLabel(pht('Comments'))
      ->setBulkEditLabel(pht('Add comment'))
      ->setBulkEditGroupKey('comments')
      ->setAliases(array('comments'))
      ->setIsFormField(false)
      ->setCanApplyWithoutEditCapability($is_interact)
      ->setTransactionType($comment_type)
      ->setConduitDescription(pht('Make comments.'))
      ->setConduitTypeDescription(
        pht('Comment to add, formatted as remarkup.'))
      ->setValue(null);

    return array(
      $comment_field,
    );
  }

}
