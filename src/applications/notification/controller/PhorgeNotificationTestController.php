<?php

final class PhorgeNotificationTestController
  extends PhorgeNotificationController {

  public function handleRequest(AphrontRequest $request) {
    $viewer = $request->getViewer();

    if ($request->validateCSRF()) {
      $message_text = pht(
        'This is a test notification, sent at %s.',
        phorge_datetime(time(), $viewer));

      // NOTE: Currently, the FeedStoryPublisher explicitly filters out
      // notifications about your own actions. Send this notification from
      // a different actor to get around this.
      $application_phid = id(new PhorgeNotificationsApplication())
        ->getPHID();

      $xactions = array();

      $xactions[] = id(new PhorgeUserTransaction())
        ->setTransactionType(
          PhorgeUserNotifyTransaction::TRANSACTIONTYPE)
        ->setNewValue($message_text)
        ->setForceNotifyPHIDs(array($viewer->getPHID()));

      $editor = id(new PhorgeUserTransactionEditor())
        ->setActor($viewer)
        ->setActingAsPHID($application_phid)
        ->setContentSourceFromRequest($request);

      $editor->applyTransactions($viewer, $xactions);
    }

    return id(new AphrontAjaxResponse());
  }

}
