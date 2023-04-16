<?php

final class PhorgeBadgesEditor
  extends PhorgeApplicationTransactionEditor {

  public function getEditorApplicationClass() {
    return 'PhorgeBadgesApplication';
  }

  public function getEditorObjectsDescription() {
    return pht('Badges');
  }

  public function getCreateObjectTitle($author, $object) {
    return pht('%s created this badge.', $author);
  }

  public function getCreateObjectTitleForFeed($author, $object) {
    return pht('%s created %s.', $author, $object);
  }

  protected function supportsSearch() {
    return true;
  }

  public function getTransactionTypes() {
    $types = parent::getTransactionTypes();
    $types[] = PhorgeTransactions::TYPE_COMMENT;
    $types[] = PhorgeTransactions::TYPE_EDGE;
    $types[] = PhorgeTransactions::TYPE_EDIT_POLICY;

    return $types;
  }

  protected function shouldSendMail(
    PhorgeLiskDAO $object,
    array $xactions) {
    return true;
  }

  public function getMailTagsMap() {
    return array(
      PhorgeBadgesTransaction::MAILTAG_DETAILS =>
        pht('Someone changes the badge\'s details.'),
      PhorgeBadgesTransaction::MAILTAG_COMMENT =>
        pht('Someone comments on a badge.'),
      PhorgeBadgesTransaction::MAILTAG_OTHER =>
        pht('Other badge activity not listed above occurs.'),
    );
  }

  protected function shouldPublishFeedStory(
    PhorgeLiskDAO $object,
    array $xactions) {
    return true;
  }

  protected function expandTransactions(
    PhorgeLiskDAO $object,
    array $xactions) {

    $actor = $this->getActor();
    $actor_phid = $actor->getPHID();

    $results = parent::expandTransactions($object, $xactions);

    // Automatically subscribe the author when they create a badge.
    if ($this->getIsNewObject()) {
      if ($actor_phid) {
        $results[] = id(new PhorgeBadgesTransaction())
          ->setTransactionType(PhorgeTransactions::TYPE_SUBSCRIBERS)
          ->setNewValue(
            array(
              '+' => array($actor_phid => $actor_phid),
            ));
      }
    }

    return $results;
  }

  protected function buildReplyHandler(PhorgeLiskDAO $object) {
    return id(new PhorgeBadgesReplyHandler())
      ->setMailReceiver($object);
  }

  protected function buildMailTemplate(PhorgeLiskDAO $object) {
    $name = $object->getName();
    $id = $object->getID();
    $subject = pht('Badge %d: %s', $id, $name);

    return id(new PhorgeMetaMTAMail())
      ->setSubject($subject);
  }

  protected function getMailTo(PhorgeLiskDAO $object) {
    return array(
      $object->getCreatorPHID(),
      $this->requireActor()->getPHID(),
    );
  }

  protected function buildMailBody(
    PhorgeLiskDAO $object,
    array $xactions) {

    $body = parent::buildMailBody($object, $xactions);

    $body->addLinkSection(
      pht('BADGE DETAIL'),
      PhorgeEnv::getProductionURI('/badges/view/'.$object->getID().'/'));
    return $body;
  }

  protected function getMailSubjectPrefix() {
    return pht('[Badge]');
  }

  protected function applyFinalEffects(
    PhorgeLiskDAO $object,
    array $xactions) {

    $badge_phid = $object->getPHID();
    $user_phids = array();
    $clear_everything = false;

    foreach ($xactions as $xaction) {
      switch ($xaction->getTransactionType()) {
        case PhorgeBadgesBadgeAwardTransaction::TRANSACTIONTYPE:
        case PhorgeBadgesBadgeRevokeTransaction::TRANSACTIONTYPE:
          foreach ($xaction->getNewValue() as $user_phid) {
            $user_phids[] = $user_phid;
          }
          break;
        default:
          $clear_everything = true;
          break;
      }
    }

    if ($clear_everything) {
      $awards = id(new PhorgeBadgesAwardQuery())
        ->setViewer($this->getActor())
        ->withBadgePHIDs(array($badge_phid))
        ->execute();
      foreach ($awards as $award) {
        $user_phids[] = $award->getRecipientPHID();
      }
    }

    if ($user_phids) {
      PhorgeUserCache::clearCaches(
        PhorgeUserBadgesCacheType::KEY_BADGES,
        $user_phids);
    }

    return $xactions;
  }

}
