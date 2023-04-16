<?php

final class PasteCreateMailReceiver
  extends PhorgeApplicationMailReceiver {

  protected function newApplication() {
    return new PhorgePasteApplication();
  }

  protected function processReceivedMail(
    PhorgeMetaMTAReceivedMail $mail,
    PhutilEmailAddress $target) {
    $author = $this->getAuthor();

    $title = $mail->getSubject();
    if (!$title) {
      $title = pht('Email Paste');
    }

    $xactions = array();

    $xactions[] = id(new PhorgePasteTransaction())
      ->setTransactionType(PhorgePasteContentTransaction::TRANSACTIONTYPE)
      ->setNewValue($mail->getCleanTextBody());

    $xactions[] = id(new PhorgePasteTransaction())
      ->setTransactionType(PhorgePasteTitleTransaction::TRANSACTIONTYPE)
      ->setNewValue($title);

    $paste = PhorgePaste::initializeNewPaste($author);

    $content_source = $mail->newContentSource();

    $editor = id(new PhorgePasteEditor())
      ->setActor($author)
      ->setContentSource($content_source)
      ->setContinueOnNoEffect(true);
    $xactions = $editor->applyTransactions($paste, $xactions);

    $mail->setRelatedPHID($paste->getPHID());

    $sender = $this->getSender();
    if (!$sender) {
      return;
    }

    $subject_prefix = pht('[Paste]');
    $subject = pht('You successfully created a paste.');
    $paste_uri = PhorgeEnv::getProductionURI($paste->getURI());
    $body = new PhorgeMetaMTAMailBody();
    $body->addRawSection($subject);
    $body->addTextSection(pht('PASTE LINK'), $paste_uri);

    id(new PhorgeMetaMTAMail())
      ->addTos(array($sender->getPHID()))
      ->setSubject($subject)
      ->setSubjectPrefix($subject_prefix)
      ->setFrom($sender->getPHID())
      ->setRelatedPHID($paste->getPHID())
      ->setBody($body->render())
      ->saveAndSend();
  }

}
