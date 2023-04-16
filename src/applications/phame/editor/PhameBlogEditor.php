<?php

final class PhameBlogEditor
  extends PhorgeApplicationTransactionEditor {

  public function getEditorApplicationClass() {
    return 'PhorgePhameApplication';
  }

  public function getEditorObjectsDescription() {
    return pht('Phame Blogs');
  }

  public function getCreateObjectTitle($author, $object) {
    return pht('%s created this blog.', $author);
  }

  public function getCreateObjectTitleForFeed($author, $object) {
    return pht('%s created %s.', $author, $object);
  }

  public function getTransactionTypes() {
    $types = parent::getTransactionTypes();

    $types[] = PhorgeTransactions::TYPE_VIEW_POLICY;
    $types[] = PhorgeTransactions::TYPE_EDIT_POLICY;
    $types[] = PhorgeTransactions::TYPE_INTERACT_POLICY;

    return $types;
  }

  protected function shouldSendMail(
    PhorgeLiskDAO $object,
    array $xactions) {
    return true;
  }

  protected function shouldPublishFeedStory(
    PhorgeLiskDAO $object,
    array $xactions) {
    return true;
  }

   protected function getMailTo(PhorgeLiskDAO $object) {
    $phids = array();
    $phids[] = $this->requireActor()->getPHID();
    $phids[] = $object->getCreatorPHID();

    return $phids;
  }

  protected function buildMailTemplate(PhorgeLiskDAO $object) {
    $name = $object->getName();

    return id(new PhorgeMetaMTAMail())
      ->setSubject($name);
  }

  protected function buildReplyHandler(PhorgeLiskDAO $object) {
    return id(new PhameBlogReplyHandler())
      ->setMailReceiver($object);
  }

  protected function buildMailBody(
    PhorgeLiskDAO $object,
    array $xactions) {

    $body = parent::buildMailBody($object, $xactions);

    $body->addLinkSection(
      pht('BLOG DETAIL'),
      PhorgeEnv::getProductionURI($object->getViewURI()));

    return $body;
  }

  public function getMailTagsMap() {
    return array(
      PhameBlogTransaction::MAILTAG_DETAILS =>
        pht("A blog's details change."),
      PhameBlogTransaction::MAILTAG_SUBSCRIBERS =>
        pht("A blog's subscribers change."),
      PhameBlogTransaction::MAILTAG_OTHER =>
        pht('Other blog activity not listed above occurs.'),
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

    return id(new HeraldPhameBlogAdapter())
      ->setBlog($object);
  }

}
