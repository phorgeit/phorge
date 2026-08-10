<?php

final class PhabricatorObjectHasMentionRelationship
  extends PhabricatorObjectRelationship {

  const RELATIONSHIPKEY = 'object.has-mention';

  public function isEnabledForObject($object) {
    return ($object instanceof PhabricatorMentionableInterface);
  }

  public function getEdgeConstant() {
    return PhabricatorObjectMentionsObjectEdgeType::EDGECONST;
  }

  protected function getActionName() {
    return pht('Edit Mentions');
  }

  protected function getActionIcon() {
    return 'fa-at';
  }

  public function canRelateObjects($src, $dst) {
    return ($dst instanceof PhabricatorMentionableInterface);
  }

  public function shouldAppearInActionMenu() {
    return true;
  }

  public function getDialogTitleText() {
    return pht('Edit Mentions');
  }

  public function getDialogHeaderText() {
    return pht('Current Mentions');
  }

  public function getDialogButtonText() {
    return pht('Save Mentions');
  }

  protected function newRelationshipSource() {
    return id(new PhabricatorMentionableRelationshipSource())
      ->setSelectedFilter('all');
  }

}
