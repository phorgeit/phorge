<?php

final class PhorgeMacroEditor
  extends PhorgeApplicationTransactionEditor {

  public function getEditorApplicationClass() {
    return 'PhorgeMacroApplication';
  }

  public function getEditorObjectsDescription() {
    return pht('Macros');
  }

  public function getCreateObjectTitle($author, $object) {
    return pht('%s created this macro.', $author);
  }

  public function getCreateObjectTitleForFeed($author, $object) {
    return pht('%s created %s.', $author, $object);
  }

  protected function shouldSendMail(
    PhorgeLiskDAO $object,
    array $xactions) {
    return true;
  }

  protected function buildReplyHandler(PhorgeLiskDAO $object) {
    return id(new PhorgeMacroReplyHandler())
      ->setMailReceiver($object);
  }

  protected function buildMailTemplate(PhorgeLiskDAO $object) {
    $name = $object->getName();
    $name = 'Image Macro "'.$name.'"';

    return id(new PhorgeMetaMTAMail())
      ->setSubject($name);
  }

  protected function getMailTo(PhorgeLiskDAO $object) {
    return array(
      $this->requireActor()->getPHID(),
    );
  }

  protected function buildMailBody(
    PhorgeLiskDAO $object,
    array $xactions) {

    $body = parent::buildMailBody($object, $xactions);
    $body->addLinkSection(
      pht('MACRO DETAIL'),
      PhorgeEnv::getProductionURI('/macro/view/'.$object->getID().'/'));

    return $body;
  }

  protected function getMailSubjectPrefix() {
    return pht('[Macro]');
  }

  protected function shouldPublishFeedStory(
    PhorgeLiskDAO $object,
    array $xactions) {
    return true;
  }
}
