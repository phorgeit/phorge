<?php

final class MetaMTAMailSentGarbageCollector
  extends PhorgeGarbageCollector {

  const COLLECTORCONST = 'metamta.sent';

  public function getCollectorName() {
    return pht('Mail (Sent)');
  }

  public function getDefaultRetentionPolicy() {
    return phutil_units('90 days in seconds');
  }

  protected function collectGarbage() {
    $mails = id(new PhorgeMetaMTAMail())->loadAllWhere(
      'dateCreated < %d LIMIT 100',
      $this->getGarbageEpoch());

    $engine = new PhorgeDestructionEngine();
    foreach ($mails as $mail) {
      $engine->destroyObject($mail);
    }

    return (count($mails) == 100);
  }

}
