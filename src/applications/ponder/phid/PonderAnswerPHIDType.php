<?php

final class PonderAnswerPHIDType extends PhorgePHIDType {

  const TYPECONST = 'ANSW';

  public function getTypeName() {
    return pht('Ponder Answer');
  }

  public function getPHIDTypeApplicationClass() {
    return 'PhorgePonderApplication';
  }

  public function newObject() {
    return new PonderAnswer();
  }

  protected function buildQueryForObjects(
    PhorgeObjectQuery $query,
    array $phids) {

    return id(new PonderAnswerQuery())
      ->withPHIDs($phids);
  }

  public function loadHandles(
    PhorgeHandleQuery $query,
    array $handles,
    array $objects) {

    foreach ($handles as $phid => $handle) {
      $answer = $objects[$phid];

      $id = $answer->getID();
      $question = $answer->getQuestion();
      $question_title = $question->getFullTitle();

      $handle->setName(pht('%s (Answer %s)', $question_title, $id));
      $handle->setURI($answer->getURI());
    }
  }

}
