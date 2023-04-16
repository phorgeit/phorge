<?php

final class HeraldCommentAction extends HeraldAction {

  const ACTIONCONST = 'comment';
  const DO_COMMENT = 'do.comment';

  public function getHeraldActionName() {
    return pht('Add comment');
  }

  public function getActionGroupKey() {
    return HeraldUtilityActionGroup::ACTIONGROUPKEY;
  }

  public function supportsObject($object) {
    if (!($object instanceof PhorgeApplicationTransactionInterface)) {
      return false;
    }

    $xaction = $object->getApplicationTransactionTemplate();

    $comment = $xaction->getApplicationTransactionCommentObject();
    if (!$comment) {
      return false;
    }

    return true;
  }

  public function supportsRuleType($rule_type) {
    return ($rule_type != HeraldRuleTypeConfig::RULE_TYPE_PERSONAL);
  }

  public function applyEffect($object, HeraldEffect $effect) {
    $adapter = $this->getAdapter();
    $comment_text = $effect->getTarget();

    $xaction = $adapter->newTransaction()
      ->setTransactionType(PhorgeTransactions::TYPE_COMMENT);

    $comment = $xaction->getApplicationTransactionCommentObject()
      ->setContent($comment_text);

    $xaction->attachComment($comment);

    $adapter->queueTransaction($xaction);

    $this->logEffect(self::DO_COMMENT, $comment_text);
  }

  public function getHeraldActionStandardType() {
    return self::STANDARD_REMARKUP;
  }

  protected function getActionEffectMap() {
    return array(
      self::DO_COMMENT => array(
        'icon' => 'fa-comment',
        'color' => 'blue',
        'name' => pht('Added Comment'),
      ),
    );
  }

  public function renderActionDescription($value) {
    $summary = PhorgeMarkupEngine::summarize($value);
    return pht('Add comment: %s', $summary);
  }

  protected function renderActionEffectDescription($type, $data) {
    $summary = PhorgeMarkupEngine::summarize($data);
    return pht('Added a comment: %s', $summary);
  }

}
