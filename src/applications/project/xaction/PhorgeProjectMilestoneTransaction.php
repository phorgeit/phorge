<?php

final class PhorgeProjectMilestoneTransaction
  extends PhorgeProjectTypeTransaction {

  const TRANSACTIONTYPE = 'project:milestone';

  public function generateOldValue($object) {
    return null;
  }

  public function applyInternalEffects($object, $value) {
    $parent_phid = $value;
    $project = id(new PhorgeProjectQuery())
      ->setViewer($this->getActor())
      ->withPHIDs(array($parent_phid))
      ->requireCapabilities(
        array(
          PhorgePolicyCapability::CAN_VIEW,
          PhorgePolicyCapability::CAN_EDIT,
        ))
      ->executeOne();

    $object->attachParentProject($project);

    $number = $object->getParentProject()->loadNextMilestoneNumber();
    $object->setMilestoneNumber($number);
    $object->setParentProjectPHID($value);
  }

}
