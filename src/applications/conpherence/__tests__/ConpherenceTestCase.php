<?php

abstract class ConpherenceTestCase extends PhorgeTestCase {

  protected function addParticipants(
    PhorgeUser $actor,
    ConpherenceThread $conpherence,
    array $participant_phids) {

    $xactions = array(
      id(new ConpherenceTransaction())
        ->setTransactionType(
          ConpherenceThreadParticipantsTransaction::TRANSACTIONTYPE)
        ->setNewValue(array('+' => $participant_phids)),
    );
    $editor = id(new ConpherenceEditor())
      ->setActor($actor)
      ->setContentSource($this->newContentSource())
      ->applyTransactions($conpherence, $xactions);

  }

  protected function removeParticipants(
    PhorgeUser $actor,
    ConpherenceThread $conpherence,
    array $participant_phids) {

    $xactions = array(
      id(new ConpherenceTransaction())
        ->setTransactionType(
          ConpherenceThreadParticipantsTransaction::TRANSACTIONTYPE)
        ->setNewValue(array('-' => $participant_phids)),
    );
    $editor = id(new ConpherenceEditor())
      ->setActor($actor)
      ->setContentSource($this->newContentSource())
      ->applyTransactions($conpherence, $xactions);
  }

  protected function addMessageWithFile(
    PhorgeUser $actor,
    ConpherenceThread $conpherence) {

    $file = $this->generateTestFile($actor);
    $message = Filesystem::readRandomCharacters(64).
      sprintf(' {%s} ', $file->getMonogram());

    $editor = id(new ConpherenceEditor())
      ->setActor($actor)
      ->setContentSource($this->newContentSource());

    $xactions = $editor->generateTransactionsFromText(
      $actor,
      $conpherence,
      $message);

    return $editor->applyTransactions($conpherence, $xactions);
  }

  private function generateTestFile(PhorgeUser $actor) {
    $engine = new PhorgeTestStorageEngine();
    $data = Filesystem::readRandomCharacters(64);

    $params = array(
      'name' => 'test.'.$actor->getPHID(),
      'viewPolicy' => $actor->getPHID(),
      'authorPHID' => $actor->getPHID(),
      'storageEngines' => array(
        $engine,
      ),
    );

    $file = PhorgeFile::newFromFileData($data, $params);
    $file->save();

    return $file;
  }

}
