<?php

final class PhorgeTokenUIEventListener
  extends PhorgeEventListener {

  public function register() {
    $this->listen(PhorgeEventType::TYPE_UI_DIDRENDERACTIONS);
    $this->listen(PhorgeEventType::TYPE_UI_WILLRENDERPROPERTIES);
  }

  public function handleEvent(PhutilEvent $event) {
    $object = $event->getValue('object');

    switch ($event->getType()) {
      case PhorgeEventType::TYPE_UI_DIDRENDERACTIONS:
        $this->handleActionEvent($event);
        break;
      case PhorgeEventType::TYPE_UI_WILLRENDERPROPERTIES:
        // Hacky solution so that property list view on Diffusion
        // commits shows build status, but not Projects, Subscriptions,
        // or Tokens.
        if ($object instanceof PhorgeRepositoryCommit) {
          return;
        }
        $this->handlePropertyEvent($event);
        break;
    }
  }

  private function handleActionEvent($event) {
    $user = $event->getUser();
    $object = $event->getValue('object');

    if (!$object || !$object->getPHID()) {
      // No object, or the object has no PHID yet..
      return;
    }

    if (!($object instanceof PhorgeTokenReceiverInterface)) {
      // This object isn't a token receiver.
      return;
    }

    if (!$this->canUseApplication($event->getUser())) {
      return null;
    }

    $can_interact = PhorgePolicyFilter::canInteract($user, $object);

    $current = id(new PhorgeTokenGivenQuery())
      ->setViewer($user)
      ->withAuthorPHIDs(array($user->getPHID()))
      ->withObjectPHIDs(array($object->getPHID()))
      ->execute();

    if (!$current) {
      $token_action = id(new PhorgeActionView())
        ->setWorkflow(true)
        ->setHref('/token/give/'.$object->getPHID().'/')
        ->setName(pht('Award Token'))
        ->setIcon('fa-trophy')
        ->setDisabled(!$can_interact);
    } else {
      $token_action = id(new PhorgeActionView())
        ->setWorkflow(true)
        ->setHref('/token/give/'.$object->getPHID().'/')
        ->setName(pht('Rescind Token'))
        ->setIcon('fa-trophy')
        ->setDisabled(!$can_interact);
    }

    if (!$user->isLoggedIn()) {
      $token_action->setDisabled(true);
    }

    $actions = $event->getValue('actions');
    $actions[] = $token_action;
    $event->setValue('actions', $actions);
  }

  private function handlePropertyEvent($event) {
    $user = $event->getUser();
    $object = $event->getValue('object');

    if (!$object || !$object->getPHID()) {
      // No object, or the object has no PHID yet..
      return;
    }

    if (!($object instanceof PhorgeTokenReceiverInterface)) {
      // This object isn't a token receiver.
      return;
    }

    if (!$this->canUseApplication($event->getUser())) {
      return null;
    }

    $limit = 1;

    $tokens_given = id(new PhorgeTokenGivenQuery())
      ->setViewer($user)
      ->withObjectPHIDs(array($object->getPHID()))
      ->execute();

    if (!$tokens_given) {
      return;
    }

    $tokens = id(new PhorgeTokenQuery())
      ->setViewer($user)
      ->withPHIDs(mpull($tokens_given, 'getTokenPHID'))
      ->execute();
    $tokens = mpull($tokens, null, 'getPHID');

    $author_phids = mpull($tokens_given, 'getAuthorPHID');
    $handles = id(new PhorgeHandleQuery())
      ->setViewer($user)
      ->withPHIDs($author_phids)
      ->execute();

    Javelin::initBehavior('phorge-tooltips');

    $list = array();
    foreach ($tokens_given as $token_given) {
      if (!idx($tokens, $token_given->getTokenPHID())) {
        continue;
      }

      $token = $tokens[$token_given->getTokenPHID()];
      $aural = javelin_tag(
        'span',
        array(
          'aural' => true,
        ),
        pht(
          '"%s" token, awarded by %s.',
          $token->getName(),
          $handles[$token_given->getAuthorPHID()]->getName()));

      $list[] = javelin_tag(
        'span',
        array(
          'sigil' => 'has-tooltip',
          'class' => 'token-icon',
          'meta' => array(
            'tip' => $handles[$token_given->getAuthorPHID()]->getName(),
          ),
        ),
        array(
          $aural,
          $token->renderIcon(),
        ));
    }

    $view = $event->getValue('view');
    $view->addProperty(pht('Tokens'), $list);
  }

}
