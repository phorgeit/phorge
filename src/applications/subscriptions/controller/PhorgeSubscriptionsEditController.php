<?php

final class PhorgeSubscriptionsEditController
  extends PhorgeController {

  public function handleRequest(AphrontRequest $request) {
    $viewer = $request->getViewer();
    $phid = $request->getURIData('phid');
    $action = $request->getURIData('action');

    if (!$request->isFormOrHisecPost()) {
      return new Aphront400Response();
    }

    switch ($action) {
      case 'add':
        $is_add = true;
        break;
      case 'delete':
        $is_add = false;
        break;
      default:
        return new Aphront400Response();
    }

    $handle = id(new PhorgeHandleQuery())
      ->setViewer($viewer)
      ->withPHIDs(array($phid))
      ->executeOne();

    $object = id(new PhorgeObjectQuery())
      ->setViewer($viewer)
      ->withPHIDs(array($phid))
      ->executeOne();

    if (!($object instanceof PhorgeSubscribableInterface)) {
      return $this->buildErrorResponse(
        pht('Bad Object'),
        pht('This object is not subscribable.'),
        $handle->getURI());
    }

    if ($object->isAutomaticallySubscribed($viewer->getPHID())) {
      return $this->buildErrorResponse(
        pht('Automatically Subscribed'),
        pht('You are automatically subscribed to this object.'),
        $handle->getURI());
    }

    if ($object instanceof PhorgeApplicationTransactionInterface) {
      if ($is_add) {
        $xaction_value = array(
          '+' => array($viewer->getPHID()),
        );
      } else {
        $xaction_value = array(
          '-' => array($viewer->getPHID()),
        );
      }

      $xaction = id($object->getApplicationTransactionTemplate())
        ->setTransactionType(PhorgeTransactions::TYPE_SUBSCRIBERS)
        ->setNewValue($xaction_value);

      $editor = id($object->getApplicationTransactionEditor())
        ->setActor($viewer)
        ->setCancelURI($handle->getURI())
        ->setContinueOnNoEffect(true)
        ->setContinueOnMissingFields(true)
        ->setContentSourceFromRequest($request);

      $editor->applyTransactions($object, array($xaction));
    } else {

      // TODO: Eventually, get rid of this once everything implements
      // PhorgeApplicationTransactionInterface.

      $editor = id(new PhorgeSubscriptionsEditor())
        ->setActor($viewer)
        ->setObject($object);

      if ($is_add) {
        $editor->subscribeExplicit(array($viewer->getPHID()), $explicit = true);
      } else {
        $editor->unsubscribe(array($viewer->getPHID()));
      }

      $editor->save();
    }

    // TODO: We should just render the "Unsubscribe" action and swap it out
    // in the document for Ajax requests.
    return id(new AphrontReloadResponse())->setURI($handle->getURI());
  }

  private function buildErrorResponse($title, $message, $uri) {
    $request = $this->getRequest();
    $viewer = $request->getUser();

    $dialog = id(new AphrontDialogView())
      ->setUser($viewer)
      ->setTitle($title)
      ->appendChild($message)
      ->addCancelButton($uri);

    return id(new AphrontDialogResponse())->setDialog($dialog);
  }

}
