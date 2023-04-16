<?php

final class PhorgePasteEditEngine
  extends PhorgeEditEngine {

  const ENGINECONST = 'paste.paste';

  public function getEngineName() {
    return pht('Pastes');
  }

  public function getSummaryHeader() {
    return pht('Configure Paste Forms');
  }

  public function getSummaryText() {
    return pht('Configure creation and editing forms in Paste.');
  }

  public function getEngineApplicationClass() {
    return 'PhorgePasteApplication';
  }

  protected function newEditableObject() {
    return PhorgePaste::initializeNewPaste($this->getViewer());
  }

  protected function newObjectQuery() {
    return id(new PhorgePasteQuery())
      ->needRawContent(true);
  }

  protected function getObjectCreateTitleText($object) {
    return pht('Create New Paste');
  }

  protected function getObjectEditTitleText($object) {
    return pht('Edit Paste: %s', $object->getTitle());
  }

  protected function getObjectEditShortText($object) {
    return $object->getMonogram();
  }

  protected function getObjectCreateShortText() {
    return pht('Create Paste');
  }

  protected function getObjectName() {
    return pht('Paste');
  }

  protected function getCommentViewHeaderText($object) {
    return pht('Eat Paste');
  }

  protected function getCommentViewButtonText($object) {
    return pht('Nom Nom Nom Nom Nom');
  }

  protected function getObjectViewURI($object) {
    return '/P'.$object->getID();
  }

  protected function buildCustomEditFields($object) {
    return array(
      id(new PhorgeTextEditField())
        ->setKey('title')
        ->setLabel(pht('Title'))
        ->setTransactionType(PhorgePasteTitleTransaction::TRANSACTIONTYPE)
        ->setDescription(pht('The title of the paste.'))
        ->setConduitDescription(pht('Retitle the paste.'))
        ->setConduitTypeDescription(pht('New paste title.'))
        ->setValue($object->getTitle()),
      id(new PhorgeDatasourceEditField())
        ->setKey('language')
        ->setLabel(pht('Language'))
        ->setTransactionType(
          PhorgePasteLanguageTransaction::TRANSACTIONTYPE)
        ->setAliases(array('lang'))
        ->setIsCopyable(true)
        ->setDatasource(new PasteLanguageSelectDatasource())
        ->setDescription(
          pht(
            'Language used for syntax highlighting. By default, inferred '.
            'from the title.'))
        ->setConduitDescription(
          pht('Change language used for syntax highlighting.'))
        ->setConduitTypeDescription(pht('New highlighting language.'))
        ->setSingleValue($object->getLanguage()),
      id(new PhorgeTextAreaEditField())
        ->setKey('text')
        ->setLabel(pht('Text'))
        ->setTransactionType(
          PhorgePasteContentTransaction::TRANSACTIONTYPE)
        ->setMonospaced(true)
        ->setHeight(AphrontFormTextAreaControl::HEIGHT_VERY_TALL)
        ->setDescription(pht('The main body text of the paste.'))
        ->setConduitDescription(pht('Change the paste content.'))
        ->setConduitTypeDescription(pht('New body content.'))
        ->setValue($object->getRawContent()),
      id(new PhorgeSelectEditField())
        ->setKey('status')
        ->setLabel(pht('Status'))
        ->setTransactionType(
          PhorgePasteStatusTransaction::TRANSACTIONTYPE)
        ->setIsFormField(false)
        ->setOptions(PhorgePaste::getStatusNameMap())
        ->setDescription(pht('Active or archived status.'))
        ->setConduitDescription(pht('Active or archive the paste.'))
        ->setConduitTypeDescription(pht('New paste status constant.'))
        ->setValue($object->getStatus()),
    );
  }

}
