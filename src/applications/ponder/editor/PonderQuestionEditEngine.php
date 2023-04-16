<?php

final class PonderQuestionEditEngine
  extends PhorgeEditEngine {

  const ENGINECONST = 'ponder.question';

  public function getEngineName() {
    return pht('Ponder Question');
  }

  public function getEngineApplicationClass() {
    return 'PhorgePonderApplication';
  }

  public function getSummaryHeader() {
    return pht('Configure Ponder Question Forms');
  }

  public function getSummaryText() {
    return pht('Configure creation and editing forms in Ponder Questions.');
  }

  public function isEngineConfigurable() {
    return false;
  }

  protected function newEditableObject() {
    return PonderQuestion::initializeNewQuestion($this->getViewer());
  }

  protected function newObjectQuery() {
    return new PonderQuestionQuery();
  }

  protected function getObjectCreateTitleText($object) {
    return pht('Create New Question');
  }

  protected function getObjectEditTitleText($object) {
    return pht('Edit Question: %s', $object->getTitle());
  }

  protected function getObjectEditShortText($object) {
    return $object->getTitle();
  }

  protected function getObjectCreateShortText() {
    return pht('New Question');
  }

  protected function getObjectName() {
    return pht('Question');
  }

  protected function getObjectCreateCancelURI($object) {
    return $this->getApplication()->getApplicationURI('/');
  }

  protected function getEditorURI() {
    return $this->getApplication()->getApplicationURI('question/edit/');
  }

  protected function getObjectViewURI($object) {
    return $object->getViewURI();
  }

  protected function buildCustomEditFields($object) {

    return array(
      id(new PhorgeTextEditField())
        ->setKey('title')
        ->setLabel(pht('Question'))
        ->setDescription(pht('Question title.'))
        ->setConduitTypeDescription(pht('New question title.'))
        ->setTransactionType(
          PonderQuestionTitleTransaction::TRANSACTIONTYPE)
        ->setValue($object->getTitle())
        ->setIsRequired(true),
      id(new PhorgeRemarkupEditField())
        ->setKey('content')
        ->setLabel(pht('Details'))
        ->setDescription(pht('Long details of the question.'))
        ->setConduitTypeDescription(pht('New question details.'))
        ->setValue($object->getContent())
        ->setTransactionType(
          PonderQuestionContentTransaction::TRANSACTIONTYPE),
      id(new PhorgeRemarkupEditField())
        ->setKey('answerWiki')
        ->setLabel(pht('Answer Summary'))
        ->setDescription(pht('Answer summary of the question.'))
        ->setConduitTypeDescription(pht('New question answer summary.'))
        ->setValue($object->getAnswerWiki())
        ->setTransactionType(
          PonderQuestionAnswerWikiTransaction::TRANSACTIONTYPE),
      id(new PhorgeSelectEditField())
        ->setKey('status')
        ->setLabel(pht('Status'))
        ->setDescription(pht('Status of the question.'))
        ->setConduitTypeDescription(pht('New question status.'))
        ->setValue($object->getStatus())
        ->setTransactionType(
          PonderQuestionStatusTransaction::TRANSACTIONTYPE)
        ->setOptions(PonderQuestionStatus::getQuestionStatusMap()),

    );
  }

}
