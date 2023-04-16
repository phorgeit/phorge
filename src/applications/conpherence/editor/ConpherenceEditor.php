<?php

final class ConpherenceEditor extends PhorgeApplicationTransactionEditor {

  const ERROR_EMPTY_PARTICIPANTS = 'error-empty-participants';
  const ERROR_EMPTY_MESSAGE = 'error-empty-message';

  public function getEditorApplicationClass() {
    return 'PhorgeConpherenceApplication';
  }

  public function getEditorObjectsDescription() {
    return pht('Conpherence Rooms');
  }

  public static function createThread(
    PhorgeUser $creator,
    array $participant_phids,
    $title,
    $message,
    PhorgeContentSource $source,
    $topic) {

    $conpherence = ConpherenceThread::initializeNewRoom($creator);
    $errors = array();
    if (empty($participant_phids)) {
      $errors[] = self::ERROR_EMPTY_PARTICIPANTS;
    } else {
      $participant_phids[] = $creator->getPHID();
      $participant_phids = array_unique($participant_phids);
    }

    if (empty($message)) {
      $errors[] = self::ERROR_EMPTY_MESSAGE;
    }

    if (!$errors) {
      $xactions = array();
      $xactions[] = id(new ConpherenceTransaction())
        ->setTransactionType(
          ConpherenceThreadParticipantsTransaction::TRANSACTIONTYPE)
        ->setNewValue(array('+' => $participant_phids));
      if ($title) {
        $xactions[] = id(new ConpherenceTransaction())
          ->setTransactionType(
            ConpherenceThreadTitleTransaction::TRANSACTIONTYPE)
          ->setNewValue($title);
      }
      if (strlen($topic)) {
        $xactions[] = id(new ConpherenceTransaction())
          ->setTransactionType(
            ConpherenceThreadTopicTransaction::TRANSACTIONTYPE)
          ->setNewValue($topic);
      }

      $xactions[] = id(new ConpherenceTransaction())
        ->setTransactionType(PhorgeTransactions::TYPE_COMMENT)
        ->attachComment(
          id(new ConpherenceTransactionComment())
          ->setContent($message)
          ->setConpherencePHID($conpherence->getPHID()));

      id(new ConpherenceEditor())
        ->setActor($creator)
        ->setContentSource($source)
        ->setContinueOnNoEffect(true)
        ->applyTransactions($conpherence, $xactions);
    }

    return array($errors, $conpherence);
  }

  public function generateTransactionsFromText(
    PhorgeUser $viewer,
    ConpherenceThread $conpherence,
    $text) {

    $xactions = array();
    $xactions[] = id(new ConpherenceTransaction())
      ->setTransactionType(PhorgeTransactions::TYPE_COMMENT)
      ->attachComment(
        id(new ConpherenceTransactionComment())
        ->setContent($text)
        ->setConpherencePHID($conpherence->getPHID()));
    return $xactions;
  }

  public function getTransactionTypes() {
    $types = parent::getTransactionTypes();

    $types[] = PhorgeTransactions::TYPE_COMMENT;
    $types[] = PhorgeTransactions::TYPE_VIEW_POLICY;
    $types[] = PhorgeTransactions::TYPE_EDIT_POLICY;

    return $types;
  }

  public function getCreateObjectTitle($author, $object) {
    return pht('%s created this room.', $author);
  }


  protected function applyBuiltinInternalTransaction(
    PhorgeLiskDAO $object,
    PhorgeApplicationTransaction $xaction) {

    switch ($xaction->getTransactionType()) {
      case PhorgeTransactions::TYPE_COMMENT:
        $object->setMessageCount((int)$object->getMessageCount() + 1);
        break;
    }

    return parent::applyBuiltinInternalTransaction($object, $xaction);
  }


  protected function applyFinalEffects(
    PhorgeLiskDAO $object,
    array $xactions) {

    $acting_phid = $this->getActingAsPHID();
    $participants = $object->getParticipants();
    foreach ($participants as $participant) {
      if ($participant->getParticipantPHID() == $acting_phid) {
        $participant->markUpToDate($object);
      }
    }

    if ($participants) {
      PhorgeUserCache::clearCaches(
        PhorgeUserMessageCountCacheType::KEY_COUNT,
        array_keys($participants));
    }

    if ($xactions) {
      $data = array(
        'type'        => 'message',
        'threadPHID'  => $object->getPHID(),
        'messageID'   => last($xactions)->getID(),
        'subscribers' => array($object->getPHID()),
      );

      PhorgeNotificationClient::tryToPostMessage($data);
    }

    return $xactions;
  }

  protected function shouldSendMail(
    PhorgeLiskDAO $object,
    array $xactions) {
    return true;
  }

  protected function buildReplyHandler(PhorgeLiskDAO $object) {
    return id(new ConpherenceReplyHandler())
      ->setActor($this->getActor())
      ->setMailReceiver($object);
  }

  protected function buildMailTemplate(PhorgeLiskDAO $object) {
    $id = $object->getID();
    $title = $object->getTitle();
    if (!$title) {
      $title = pht(
        '%s sent you a message.',
        $this->getActor()->getUserName());
    }

    return id(new PhorgeMetaMTAMail())
      ->setSubject("Z{$id}: {$title}");
  }

  protected function getMailTo(PhorgeLiskDAO $object) {
    $to_phids = array();

    $participants = $object->getParticipants();
    if (!$participants) {
      return $to_phids;
    }

    $participant_phids = mpull($participants, 'getParticipantPHID');

    $users = id(new PhorgePeopleQuery())
      ->setViewer(PhorgeUser::getOmnipotentUser())
      ->withPHIDs($participant_phids)
      ->needUserSettings(true)
      ->execute();
    $users = mpull($users, null, 'getPHID');

    $notification_key = PhorgeConpherenceNotificationsSetting::SETTINGKEY;
    $notification_email =
      PhorgeConpherenceNotificationsSetting::VALUE_CONPHERENCE_EMAIL;

    foreach ($participants as $phid => $participant) {
      $user = idx($users, $phid);
      if ($user) {
        $default = $user->getUserSetting($notification_key);
      } else {
        $default = $notification_email;
      }

      $settings = $participant->getSettings();
      $notifications = idx($settings, 'notifications', $default);

      if ($notifications == $notification_email) {
        $to_phids[] = $phid;
      }
    }

    return $to_phids;
  }

  protected function getMailCC(PhorgeLiskDAO $object) {
    return array();
  }

  protected function buildMailBody(
    PhorgeLiskDAO $object,
    array $xactions) {

    $body = parent::buildMailBody($object, $xactions);
    $body->addLinkSection(
      pht('CONPHERENCE DETAIL'),
      PhorgeEnv::getProductionURI('/'.$object->getMonogram()));

    return $body;
  }

  protected function addEmailPreferenceSectionToMailBody(
    PhorgeMetaMTAMailBody $body,
    PhorgeLiskDAO $object,
    array $xactions) {

    $href = PhorgeEnv::getProductionURI(
      '/'.$object->getMonogram().'?settings');
    $label = pht('EMAIL PREFERENCES FOR THIS ROOM');
    $body->addLinkSection($label, $href);
  }

  protected function getMailSubjectPrefix() {
    return pht('[Conpherence]');
  }

  protected function supportsSearch() {
    return true;
  }

}
