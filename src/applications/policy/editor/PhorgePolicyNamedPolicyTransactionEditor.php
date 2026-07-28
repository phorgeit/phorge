<?php

final class PhorgePolicyNamedPolicyTransactionEditor
extends PhabricatorApplicationTransactionEditor {

  public function getEditorApplicationClass() {
    return PhabricatorPolicyApplication::class;
  }

  public function getEditorObjectsDescription() {
    return pht('Named Policies');
  }

  public function getTransactionTypes() {
    $types = parent::getTransactionTypes();

    $types[] = PhorgePolicyNamedPolicyNameTransaction::TRANSACTIONTYPE;
    $types[] = PhorgePolicyNamedPolicyDescriptionTransaction::TRANSACTIONTYPE;
    $types[] =
      PhorgePolicyNamedPolicyEffectivePolicyTransaction::TRANSACTIONTYPE;
    $types[] =
      PhorgePolicyNamedPolicyTargetObjectTypeTransaction::TRANSACTIONTYPE;

    $types[] = PhabricatorTransactions::TYPE_VIEW_POLICY;
    $types[] = PhabricatorTransactions::TYPE_EDIT_POLICY;

    return $types;
  }

  protected function shouldPublishFeedStory(
    PhabricatorLiskDAO $object,
     array $xactions) {

    return true;
  }

  protected function shouldSendMail(
    PhabricatorLiskDAO $object,
    array $xactions) {

    return true;
  }

  protected function buildMailTemplate(PhabricatorLiskDAO $object) {
    $name = $object->getName();
    $id = $object->getID();
    $subject = pht('Named Policy %d: %s', $id, $name);

    return id(new PhabricatorMetaMTAMail())
      ->setSubject($subject);
  }

  protected function buildReplyHandler(PhabricatorLiskDAO $object) {
    return id(new PhorgePolicyNamedPolicyReplyHandler())
      ->setMailReceiver($object);
  }

  protected function getMailTo(PhabricatorLiskDAO $object) {
    return array(
      $this->requireActor()->getPHID(),
    );
  }

  protected function buildMailBody(
    PhabricatorLiskDAO $object,
    array $xactions) {

    $body = parent::buildMailBody($object, $xactions);

    $body->addLinkSection(pht('POLICY DETAIL'), $object->getHref());
    return $body;
  }

  protected function getMailSubjectPrefix() {
    return pht('[Policy]');
  }

}
