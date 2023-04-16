<?php

final class PhorgeSubscriptionsSubscribeEmailCommand
  extends MetaMTAEmailTransactionCommand {

  public function getCommand() {
    return 'subscribe';
  }

  public function getCommandSyntax() {
    return '**!subscribe** //username #project ...//';
  }

  public function getCommandSummary() {
    return pht('Add users or projects as subscribers.');
  }

  public function getCommandDescription() {
    return pht(
      'Add one or more subscribers to the object. You can add users by '.
      'providing their usernames, or add projects by adding their hashtags. '.
      'For example, use `%s` to add the user `alincoln` and the project with '.
      'hashtag `#ios` as subscribers.'.
      "\n\n".
      'Subscribers which are invalid or unrecognized will be ignored. This '.
      'command has no effect if you do not specify any subscribers.'.
      "\n\n".
      'Users who are CC\'d on the email itself are also automatically '.
      'subscribed if their addresses are associated with a known account.',
      '!subscribe alincoln #ios');
  }

  public function getCommandAliases() {
    return array(
      'cc',
    );
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

    $subscriber_phids = id(new PhorgeObjectListQuery())
      ->setViewer($viewer)
      ->setAllowedTypes(
        array(
          PhorgePeopleUserPHIDType::TYPECONST,
          PhorgeProjectProjectPHIDType::TYPECONST,
        ))
      ->setObjectList(implode(' ', $argv))
      ->setAllowPartialResults(true)
      ->execute();

    $xactions = array();

    $xactions[] = $object->getApplicationTransactionTemplate()
      ->setTransactionType(PhorgeTransactions::TYPE_SUBSCRIBERS)
      ->setNewValue(
        array(
          '+' => array_fuse($subscriber_phids),
        ));

    return $xactions;
  }

}
