<?php

final class PhorgeTokenUIActionExtension extends PHUIActionListExtension {

  const EXTENSIONKEY = 'token';

  public function shouldEnableForObject($object) {
    return
      $object instanceof PhabricatorTokenReceiverInterface &&
      $object->getPHID();

  }

  public function getExtensionApplicationClass() {
    return PhabricatorTokensApplication::class;
  }


  protected function buildAction() {
    $viewer = $this->getViewer();
    /** @var PhabricatorTokenReceiverInterface & PhabricatorPolicyInterface */
    $object = $this->getObject();

    $can_interact = PhabricatorPolicyFilter::canInteract($viewer, $object);

    $current = id(new PhabricatorTokenGivenQuery())
      ->setViewer($viewer)
      ->withAuthorPHIDs(array($viewer->getPHID()))
      ->withObjectPHIDs(array($object->getPHID()))
      ->execute();

    if (!$current) {
      $token_action = id(new PhabricatorActionView())
        ->setWorkflow(true)
        ->setHref('/token/give/'.$object->getPHID().'/')
        ->setName(pht('Award Token'))
        ->setIcon('fa-trophy')
        ->setDisabled(!$can_interact);
    } else {
      $token_action = id(new PhabricatorActionView())
        ->setWorkflow(true)
        ->setHref('/token/give/'.$object->getPHID().'/')
        ->setName(pht('Rescind Token'))
        ->setIcon('fa-trophy')
        ->setDisabled(!$can_interact);
    }

    if (!$viewer->isLoggedIn() ||
        ($object instanceof PhorgeRestrictableInteractionInterface &&
        $object->disallowInteractions())) {
      $token_action->setDisabled(true);
    }

    $token_action->setOrder(4200);

    return $token_action;
  }

}
