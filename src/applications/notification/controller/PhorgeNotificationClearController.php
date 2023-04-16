<?php

final class PhorgeNotificationClearController
  extends PhorgeNotificationController {

  public function handleRequest(AphrontRequest $request) {
    $viewer = $request->getViewer();
    $chrono_key = $request->getStr('chronoKey');

    if ($request->isDialogFormPost()) {
      $should_clear = true;
    } else {
      $should_clear = $request->hasCSRF();
    }

    if ($should_clear) {
      $table = new PhorgeFeedStoryNotification();

      queryfx(
        $table->establishConnection('w'),
        'UPDATE %T SET hasViewed = 1 '.
        'WHERE userPHID = %s AND hasViewed = 0 and chronologicalKey <= %s',
        $table->getTableName(),
        $viewer->getPHID(),
        $chrono_key);

      PhorgeUserCache::clearCache(
        PhorgeUserNotificationCountCacheType::KEY_COUNT,
        $viewer->getPHID());

      return id(new AphrontReloadResponse())
        ->setURI('/notification/');
    }

    $dialog = new AphrontDialogView();
    $dialog->setUser($viewer);
    $dialog->addCancelButton('/notification/');
    if ($chrono_key) {
      $dialog->setTitle(pht('Really mark all notifications as read?'));
      $dialog->addHiddenInput('chronoKey', $chrono_key);

      $is_serious =
        PhorgeEnv::getEnvConfig('phorge.serious-business');
      if ($is_serious) {
        $dialog->appendChild(
          pht(
            'All unread notifications will be marked as read. You can not '.
            'undo this action.'));
      } else {
        $dialog->appendChild(
          pht(
            "You can't ignore your problems forever, you know."));
      }

      $dialog->addSubmitButton(pht('Mark All Read'));
    } else {
      $dialog->setTitle(pht('No notifications to mark as read.'));
      $dialog->appendChild(pht('You have no unread notifications.'));
    }

    return id(new AphrontDialogResponse())->setDialog($dialog);
  }
}
