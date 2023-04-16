<?php

final class HeraldRuleEditor
  extends PhorgeApplicationTransactionEditor {

  public function getEditorApplicationClass() {
    return 'PhorgeHeraldApplication';
  }

  public function getEditorObjectsDescription() {
    return pht('Herald Rules');
  }

  protected function shouldApplyHeraldRules(
    PhorgeLiskDAO $object,
    array $xactions) {
    return true;
  }

  protected function buildHeraldAdapter(
    PhorgeLiskDAO $object,
    array $xactions) {
    return id(new HeraldRuleAdapter())
      ->setRule($object);
  }

  protected function shouldSendMail(
    PhorgeLiskDAO $object,
    array $xactions) {
    return true;
  }

  public function getTransactionTypes() {
    $types = parent::getTransactionTypes();
    $types[] = PhorgeTransactions::TYPE_EDGE;
    return $types;
  }

  protected function getMailTo(PhorgeLiskDAO $object) {
    $phids = array();

    $phids[] = $this->getActingAsPHID();

    if ($object->isPersonalRule()) {
      $phids[] = $object->getAuthorPHID();
    }

    return $phids;
  }

  protected function buildReplyHandler(PhorgeLiskDAO $object) {
    return id(new HeraldRuleReplyHandler())
      ->setMailReceiver($object);
  }

  protected function buildMailTemplate(PhorgeLiskDAO $object) {
    $monogram = $object->getMonogram();
    $name = $object->getName();

    $subject = pht('%s: %s', $monogram, $name);

    return id(new PhorgeMetaMTAMail())
      ->setSubject($subject);
  }

  protected function getMailSubjectPrefix() {
    return pht('[Herald]');
  }

  protected function buildMailBody(
    PhorgeLiskDAO $object,
    array $xactions) {

    $body = parent::buildMailBody($object, $xactions);

    $body->addLinkSection(
      pht('RULE DETAIL'),
      PhorgeEnv::getProductionURI($object->getURI()));

    return $body;
  }

  protected function supportsSearch() {
    return true;
  }

}
