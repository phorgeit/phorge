<?php

final class PhabricatorFlagsUIExtension extends PHUIActionListExtension {

  const EXTENSIONKEY = 'flag';

  public function shouldEnableForObject($object) {
    return
      $object instanceof PhabricatorFlaggableInterface &&
      $object->getPHID();
  }

  public function getExtensionApplicationClass() {
    return PhabricatorFlagsApplication::class;
  }

  protected function buildAction() {
    $viewer = $this->getViewer();
    /** @var PhabricatorFlaggableInterface */
    $object = $this->getObject();


    $flag = PhabricatorFlagQuery::loadUserFlag($viewer, $object->getPHID());

    if ($flag) {
      $color = PhabricatorFlagColor::getColorName($flag->getColor());
      $flag_icon = PhabricatorFlagColor::getIcon($flag->getColor());
      $flag_action = id(new PhabricatorActionView())
        ->setWorkflow(true)
        ->setHref('/flag/delete/'.$flag->getID().'/')
        ->setName(pht('Remove %s Flag', $color))
        ->setIcon($flag_icon);
    } else {
      $flag_action = id(new PhabricatorActionView())
        ->setWorkflow(true)
        ->setHref('/flag/edit/'.$object->getPHID().'/')
        ->setName(pht('Flag For Later'))
        ->setIcon('fa-flag');

      if (!$viewer->isLoggedIn() ||
          ($object instanceof PhorgeRestrictableInteractionInterface &&
          $object->disallowInteractions())) {
        $flag_action->setDisabled(true);
      }
    }

    $flag_action->setOrder(4100);

    return $flag_action;
  }

}
