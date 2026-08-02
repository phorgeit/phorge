<?php

final class PhorgeSubscriptionsUIActionExtension
  extends PHUIActionListExtension {

  const EXTENSIONKEY = 'subscriptions';

  public function shouldEnableForObject($object) {
    return
      $object instanceof PhabricatorSubscribableInterface &&
      $object->getPHID();
  }

  public function getExtensionApplicationClass() {
    return PhabricatorSubscriptionsApplication::class;
  }

  protected function buildActions() {
    $viewer = $this->getViewer();
    /** @var PhabricatorSubscribableInterface &
     *   PhabricatorApplicationTransactionInterface */
    $object = $this->getObject();

    $user_phid = $viewer->getPHID();
    $src_phid = $object->getPHID();

    $subscribed_type = PhabricatorObjectHasSubscriberEdgeType::EDGECONST;
    $muted_type = PhabricatorMutedByEdgeType::EDGECONST;

    $edges = id(new PhabricatorEdgeQuery())
      ->withSourcePHIDs(array($src_phid))
      ->withEdgeTypes(
        array(
          $subscribed_type,
          $muted_type,
        ))
      ->withDestinationPHIDs(array($user_phid))
      ->execute();

    if ($user_phid) {
      $is_subscribed = isset($edges[$src_phid][$subscribed_type][$user_phid]);
      $is_muted = isset($edges[$src_phid][$muted_type][$user_phid]);
    } else {
      $is_subscribed = false;
      $is_muted = false;
    }

    if ($user_phid && $object->isAutomaticallySubscribed($user_phid)) {
      $sub_action = id(new PhabricatorActionView())
        ->setWorkflow(true)
        ->setDisabled(true)
        ->setRenderAsForm(true)
        ->setHref('/subscriptions/add/'.$object->getPHID().'/')
        ->setName(pht('Automatically Subscribed'))
        ->setIcon('fa-check-circle lightgreytext');
    } else {
      if ($is_subscribed) {
        $sub_action = id(new PhabricatorActionView())
          ->setWorkflow(true)
          ->setRenderAsForm(true)
          ->setHref('/subscriptions/delete/'.$object->getPHID().'/')
          ->setName(pht('Unsubscribe'))
          ->setIcon('fa-minus-circle');
      } else {
        $sub_action = id(new PhabricatorActionView())
          ->setWorkflow(true)
          ->setRenderAsForm(true)
          ->setHref('/subscriptions/add/'.$object->getPHID().'/')
          ->setName(pht('Subscribe'))
          ->setIcon('fa-plus-circle');
      }

      if (!$viewer->isLoggedIn() ||
          ($object instanceof PhorgeRestrictableInteractionInterface &&
          $object->disallowInteractions())) {
        $sub_action->setDisabled(true);
      }
    }

    $sub_action->setOrder(3000);

    $actions = array(
      $sub_action,
    );

    // Hide "Mute Notifications" in sidebar if not supported by Editor - T15378
    $supported_editor_transaction_types =
      array_fill_keys(
        $object->getApplicationTransactionEditor()
          ->getTransactionTypesForObject($object),
        true);
    if (array_key_exists(
          PhabricatorTransactions::TYPE_EDGE,
          $supported_editor_transaction_types)) {
      $mute_action = id(new PhabricatorActionView())
        ->setWorkflow(true)
        ->setHref('/subscriptions/mute/'.$object->getPHID().'/')
        ->setDisabled(!$user_phid);

      if (!$is_muted) {
        $mute_action
          ->setName(pht('Mute Notifications'))
          ->setIcon('fa-volume-up');
      } else {
        $mute_action
          ->setName(pht('Unmute Notifications'))
          ->setIcon('fa-volume-off')
          ->setColor(PhabricatorActionView::RED);
      }
      $mute_action->setOrder(3010);
      $actions[] = $mute_action;
    }

    return $actions;
  }

}
