<?php

final class HarbormasterBuildStepTransaction
  extends PhabricatorApplicationTransaction {

  const TYPE_CREATE = 'harbormaster:step:create';
  const TYPE_NAME = 'harbormaster:step:name';
  const TYPE_DEPENDS_ON = 'harbormaster:step:depends';
  const TYPE_DESCRIPTION = 'harbormaster:step:description';

  public function getApplicationName() {
    return 'harbormaster';
  }

  public function getApplicationTransactionType() {
    return HarbormasterBuildStepPHIDType::TYPECONST;
  }

  public function getTitle() {
    $author_phid = $this->getAuthorPHID();

    switch ($this->getTransactionType()) {
      case self::TYPE_CREATE:
        return pht(
          '%s created this build step.',
          $this->renderHandleLink($author_phid));
    }

    return parent::getTitle();
  }

  public function getIcon() {
    switch ($this->getTransactionType()) {
      case self::TYPE_CREATE:
        return 'fa-plus';
    }

    return parent::getIcon();
  }

  public function getColor() {
    switch ($this->getTransactionType()) {
      case self::TYPE_CREATE:
        return 'green';
    }

    return parent::getColor();
  }

}
