<?php

final class PhorgeBadgesEditEngine
  extends PhorgeEditEngine {

  const ENGINECONST = 'badges.badge';

  public function getEngineName() {
    return pht('Badges');
  }

  public function getEngineApplicationClass() {
    return 'PhorgeBadgesApplication';
  }

  public function getSummaryHeader() {
    return pht('Configure Badges Forms');
  }

  public function getSummaryText() {
    return pht('Configure creation and editing forms in Badges.');
  }

  public function isEngineConfigurable() {
    return false;
  }

  protected function newEditableObject() {
    return PhorgeBadgesBadge::initializeNewBadge($this->getViewer());
  }

  protected function newObjectQuery() {
    return new PhorgeBadgesQuery();
  }

  protected function getObjectCreateTitleText($object) {
    return pht('Create New Badge');
  }

  protected function getObjectEditTitleText($object) {
    return pht('Edit Badge: %s', $object->getName());
  }

  protected function getObjectEditShortText($object) {
    return $object->getName();
  }

  protected function getObjectCreateShortText() {
    return pht('Create Badge');
  }

  protected function getObjectName() {
    return pht('Badge');
  }

  protected function getObjectCreateCancelURI($object) {
    return $this->getApplication()->getApplicationURI('/');
  }

  protected function getEditorURI() {
    return $this->getApplication()->getApplicationURI('edit/');
  }

  protected function getCommentViewHeaderText($object) {
    return pht('Render Honors');
  }

  protected function getCommentViewButtonText($object) {
    return pht('Salute');
  }

  protected function getObjectViewURI($object) {
    return $object->getViewURI();
  }

  protected function getCreateNewObjectPolicy() {
    return $this->getApplication()->getPolicy(
      PhorgeBadgesCreateCapability::CAPABILITY);
  }

  protected function buildCustomEditFields($object) {

    return array(
      id(new PhorgeTextEditField())
        ->setKey('name')
        ->setLabel(pht('Name'))
        ->setDescription(pht('Badge name.'))
        ->setConduitTypeDescription(pht('New badge name.'))
        ->setTransactionType(
          PhorgeBadgesBadgeNameTransaction::TRANSACTIONTYPE)
        ->setValue($object->getName())
        ->setIsRequired(true),
      id(new PhorgeTextEditField())
        ->setKey('flavor')
        ->setLabel(pht('Flavor Text'))
        ->setDescription(pht('Short description of the badge.'))
        ->setConduitTypeDescription(pht('New badge flavor.'))
        ->setValue($object->getFlavor())
        ->setTransactionType(
          PhorgeBadgesBadgeFlavorTransaction::TRANSACTIONTYPE),
      id(new PhorgeIconSetEditField())
        ->setKey('icon')
        ->setLabel(pht('Icon'))
        ->setIconSet(new PhorgeBadgesIconSet())
        ->setTransactionType(
          PhorgeBadgesBadgeIconTransaction::TRANSACTIONTYPE)
        ->setConduitDescription(pht('Change the badge icon.'))
        ->setConduitTypeDescription(pht('New badge icon.'))
        ->setValue($object->getIcon()),
      id(new PhorgeSelectEditField())
        ->setKey('quality')
        ->setLabel(pht('Quality'))
        ->setDescription(pht('Color and rarity of the badge.'))
        ->setConduitTypeDescription(pht('New badge quality.'))
        ->setValue($object->getQuality())
        ->setTransactionType(
          PhorgeBadgesBadgeQualityTransaction::TRANSACTIONTYPE)
        ->setOptions(PhorgeBadgesQuality::getDropdownQualityMap()),
      id(new PhorgeRemarkupEditField())
        ->setKey('description')
        ->setLabel(pht('Description'))
        ->setDescription(pht('Badge long description.'))
        ->setConduitTypeDescription(pht('New badge description.'))
        ->setTransactionType(
          PhorgeBadgesBadgeDescriptionTransaction::TRANSACTIONTYPE)
        ->setValue($object->getDescription()),
      id(new PhorgeUsersEditField())
        ->setKey('award')
        ->setIsFormField(false)
        ->setDescription(pht('New badge award recipients.'))
        ->setConduitTypeDescription(pht('New badge award recipients.'))
        ->setTransactionType(
          PhorgeBadgesBadgeAwardTransaction::TRANSACTIONTYPE)
        ->setLabel(pht('Award Recipients')),
      id(new PhorgeUsersEditField())
        ->setKey('revoke')
        ->setIsFormField(false)
        ->setDescription(pht('Revoke badge award recipients.'))
        ->setConduitTypeDescription(pht('Revoke badge award recipients.'))
        ->setTransactionType(
          PhorgeBadgesBadgeRevokeTransaction::TRANSACTIONTYPE)
        ->setLabel(pht('Revoke Recipients')),

    );
  }

}
