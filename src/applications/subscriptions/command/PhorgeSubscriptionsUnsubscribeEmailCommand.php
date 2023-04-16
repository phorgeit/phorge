<?php

final class PhorgeSubscriptionsUnsubscribeEmailCommand
  extends MetaMTAEmailTransactionCommand {

  public function getCommand() {
    return 'unsubscribe';
  }

  public function getCommandSummary() {
    return pht('Remove yourself as a subscriber.');
  }

  public function isCommandSupportedForObject(
    PhorgeApplicationTransactionInterface $object) {
    return ($object instanceof PhorgeSubscribableInterface);
  }

  public function buildTransactions(
    PhorgeUser $viewer,
    PhorgeApplicationTransactionInterface $object,
    PhorgeMetaMTAReceivedMail $mail,
    $command,
    array $argv) {
    $xactions = array();

    $xactions[] = $object->getApplicationTransactionTemplate()
      ->setTransactionType(PhorgeTransactions::TYPE_SUBSCRIBERS)
      ->setNewValue(
        array(
          '-' => array($viewer->getPHID()),
        ));

    return $xactions;
  }

}
