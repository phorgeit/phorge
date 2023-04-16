<?php

abstract class PhorgePeopleManagementWorkflow
  extends PhorgeManagementWorkflow {

  final protected function getUserSelectionArguments() {
    return array(
      array(
        'name' => 'user',
        'param' => 'username',
        'help' => pht('User account to act on.'),
      ),
    );
  }

  final protected function selectUser(PhutilArgumentParser $argv) {
    $username = $argv->getArg('user');

    if (!strlen($username)) {
      throw new PhutilArgumentUsageException(
        pht(
          'Select a user account to act on with "--user <username>".'));
    }

    $user = id(new PhorgePeopleQuery())
      ->setViewer($this->getViewer())
      ->withUsernames(array($username))
      ->executeOne();
    if (!$user) {
      throw new PhutilArgumentUsageException(
        pht(
          'No user with username "%s" exists.',
          $username));
    }

    return $user;
  }

  final protected function applyTransactions(
    PhorgeUser $user,
    array $xactions) {
    assert_instances_of($xactions, 'PhorgeUserTransaction');

    $viewer = $this->getViewer();
    $application = id(new PhorgePeopleApplication())->getPHID();
    $content_source = $this->newContentSource();

    $editor = $user->getApplicationTransactionEditor()
      ->setActor($viewer)
      ->setActingAsPHID($application)
      ->setContentSource($content_source)
      ->setContinueOnMissingFields(true);

    return $editor->applyTransactions($user, $xactions);
  }

}
