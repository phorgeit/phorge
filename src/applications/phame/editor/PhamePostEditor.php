<?php

final class PhamePostEditor
  extends PhorgeApplicationTransactionEditor {

  public function getEditorApplicationClass() {
    return 'PhorgePhameApplication';
  }

  public function getEditorObjectsDescription() {
    return pht('Phame Posts');
  }

  public function getCreateObjectTitle($author, $object) {
    return pht('%s created this post.', $author);
  }

  public function getCreateObjectTitleForFeed($author, $object) {
    return pht('%s created %s.', $author, $object);
  }

  public function getTransactionTypes() {
    $types = parent::getTransactionTypes();

    $types[] = PhorgeTransactions::TYPE_INTERACT_POLICY;
    $types[] = PhorgeTransactions::TYPE_COMMENT;

    return $types;
  }

  protected function shouldSendMail(
    PhorgeLiskDAO $object,
    array $xactions) {
    if ($object->isDraft() || ($object->isArchived())) {
      return false;
    }
    return true;
  }

  protected function shouldPublishFeedStory(
    PhorgeLiskDAO $object,
    array $xactions) {
    if ($object->isDraft() || $object->isArchived()) {
      return false;
    }
    return true;
  }

  protected function getMailTo(PhorgeLiskDAO $object) {
    $phids = array();
    $phids[] = $object->getBloggerPHID();
    $phids[] = $this->requireActor()->getPHID();

    $blog_phid = $object->getBlogPHID();
    if ($blog_phid) {
      $cc_phids = PhorgeSubscribersQuery::loadSubscribersForPHID(
        $blog_phid);
      foreach ($cc_phids as $cc) {
        $phids[] = $cc;
      }
    }
    return $phids;
  }

  protected function buildMailTemplate(PhorgeLiskDAO $object) {
    $title = $object->getTitle();

    return id(new PhorgeMetaMTAMail())
      ->setSubject($title);
  }

  protected function buildReplyHandler(PhorgeLiskDAO $object) {
    return id(new PhamePostReplyHandler())
      ->setMailReceiver($object);
  }

  protected function buildMailBody(
    PhorgeLiskDAO $object,
    array $xactions) {

    $body = parent::buildMailBody($object, $xactions);

    // We don't send mail if the object is a draft, and we only want
    // to include the full body of the post on the either the
    // first creation or if it was created as a draft, once it goes live.
    if ($this->getIsNewObject()) {
      $body->addRemarkupSection(null, $object->getBody());
    } else {
      foreach ($xactions as $xaction) {
        switch ($xaction->getTransactionType()) {
          case PhamePostVisibilityTransaction::TRANSACTIONTYPE:
            if (!$object->isDraft() && !$object->isArchived()) {
              $body->addRemarkupSection(null, $object->getBody());
            }
          break;
        }
      }
    }

    $body->addLinkSection(
      pht('POST DETAIL'),
      PhorgeEnv::getProductionURI($object->getViewURI()));

    return $body;
  }

  public function getMailTagsMap() {
    return array(
      PhamePostTransaction::MAILTAG_CONTENT =>
        pht("A post's content changes."),
      PhamePostTransaction::MAILTAG_SUBSCRIBERS =>
        pht("A post's subscribers change."),
      PhamePostTransaction::MAILTAG_COMMENT =>
        pht('Someone comments on a post.'),
      PhamePostTransaction::MAILTAG_OTHER =>
        pht('Other post activity not listed above occurs.'),
    );
  }

  protected function getMailSubjectPrefix() {
    return '[Phame]';
  }

  protected function supportsSearch() {
    return true;
  }

  protected function shouldApplyHeraldRules(
    PhorgeLiskDAO $object,
    array $xactions) {
    return true;
  }

  protected function buildHeraldAdapter(
    PhorgeLiskDAO $object,
    array $xactions) {

    return id(new HeraldPhamePostAdapter())
      ->setPost($object);
  }

}
