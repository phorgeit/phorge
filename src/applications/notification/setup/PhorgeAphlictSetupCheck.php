<?php

final class PhorgeAphlictSetupCheck extends PhorgeSetupCheck {

  protected function executeChecks() {
    try {
      PhorgeNotificationClient::tryAnyConnection();
    } catch (Exception $ex) {
      $message = pht(
        "This server is configured to use a notification server, but is ".
        "unable to connect to it. You should resolve this issue or disable ".
        "the notification server. It may be helpful to double check your ".
        "configuration or restart the server using the command below.\n\n%s",
        phutil_tag(
          'pre',
          array(),
          array(
            get_class($ex),
            "\n",
            $ex->getMessage(),
          )));


      $this->newIssue('aphlict.connect')
        ->setShortName(pht('Notification Server Down'))
        ->setName(pht('Unable to Connect to Notification Server'))
        ->setSummary(
          pht(
            'This server is configured to use a notification server, '.
            'but is not able to connect to it.'))
        ->setMessage($message)
        ->addRelatedPhorgeConfig('notification.servers')
        ->addCommand(
          pht(
            "(To start the server, run this command.)\n%s",
            '$ ./bin/aphlict start'));

      return;
    }
  }
}
