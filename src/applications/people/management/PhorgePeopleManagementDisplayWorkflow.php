<?php

final class PhorgePeopleManagementDisplayWorkflow
extends PhabricatorPeopleManagementWorkflow {

  protected function didConstruct() {
    $arguments = array_merge(
      $this->getUserSelectionArguments(),
      array());

    $this
      ->setName('display')
      ->setExamples('**display** --user __username__')
      ->setSynopsis(pht('Display login information of a user.'))
      ->setArguments($arguments);
  }

  public function execute(PhutilArgumentParser $args) {
    $viewer = $this->getViewer();

    $user = $this->selectUser($args);

    $this->renderBasicData($user);


    $emails = id(new PhabricatorPeopleUserEmailQuery())
      ->setViewer($viewer)
      ->withUserPHIDs(array($user->getPHID()))
      ->execute();
    $this->renderEmailAddresses($emails);


    $accounts = id(new PhabricatorExternalAccountQuery())
      ->setViewer($viewer)
      ->withUserPHIDs(array($user->getPHID()))
      ->execute();
    $this->renderExternalAccounts($accounts);
  }

  /** @var PhabricatorExternalAccount[] $accounts */
  private function renderExternalAccounts($accounts) {
    $console = PhutilConsole::getConsole();

    $console->writeOut("\n\n");
    $console->writeOut(pht('External Accounts:'));

    if (empty($accounts)) {
      $console->writeOut(' ');
      $console->writeOut(pht('None found.'));
      $console->writeOut("\n");
      return;
    }
    $console->writeOut("\n");

    $rows = array();
    foreach ($accounts as $account) {

      $config = $account->getProviderConfig();
      $provider = $config->getProvider();

      $row = array(
        'phid' => $account->getPHID(),
        'can_login' => $account->isUsableForLogin() ? pht('true') :pht('false'),
        'display_name' => $account->getDisplayName(),
        'remote_user' => $account->getUsername(),
      );

      if ($provider) {
       $row['provider'] = $provider->getProviderName();
      } else {
        $row['provider'] = $account->getProviderType();
      }

      $rows[] = $row;
    }

    $table_columns = array(
      'provider' => array('title' => pht('Provider')),
      'can_login' => array('title' => pht('Can Login?')),
      'remote_user' => array('title' => pht('Username')),
      'display_name' => array('title' => pht('Display Name')),
      'domain' => array('title' => pht('Domain')),
      'phid' => array('title' => pht('PHID')),
    );
    $table = id(new PhutilConsoleTable())
      ->setBorders(true)
      ->addColumns($table_columns);
    $table->drawRows($rows);

  }

  /** @var PhabricatorUserEmail[] $emails */
  private function renderEmailAddresses($emails) {
    $console = PhutilConsole::getConsole();

    $console->writeOut("\n\n");
    $console->writeOut(pht('Email Addresses:'));

    if (empty($emails)) {
      $console->writeOut(' ');
      $console->writeOut(pht('No email addresses found.'));
      $console->writeOut("\n");
      return;
    }
    $console->writeOut("\n");

    $rows = array();
    foreach ($emails as $email) {
      $rows[] = array(
        'phid' => $email->getPHID(),
        'address' => $email->getAddress(),
        'verified' => $email->getIsVerified() ? pht('true'): pht('false'),
        'primary' => $email->getIsPrimary() ? pht('true'): pht('false'),
      );
    }

    $table_columns = array(
      'address' => array('title' => pht('Address')),
      'verified' => array('title' => pht('Verified?')),
      'primary' => array('title' => pht('Primary?')),
      'phid' => array('title' => pht('PHID')),
    );
    $table = id(new PhutilConsoleTable())
      ->addColumns($table_columns);
    $table->drawRows($rows);
  }

  private function renderBasicData(PhabricatorUser $user) {
    $viewer = $this->getViewer();

    $basic_data = array();
    $created_time = $user->getDateCreated();
    $created_time = sprintf(
      '%s  (%d)',
      phabricator_datetime($created_time, $viewer),
      $created_time);

    $flags = array();

    $flags[] = $user->getIsDisabled() ? 'Disable' : 'Enabled';

    $flag_methods = array(
      'getIsAdmin' => 'Admin',
      'getIsSystemAgent' => 'SystemAgent',
      'getIsEmailVerified' => 'EmailVerified',
      'getIsApproved' => 'Approved',
      'getIsMailingList' => 'MailingList',
      'getIsEnrolledInMultiFactor' => 'MFA',
    );

    foreach ($flag_methods as $method => $flag) {
      if ($user->$method()) {
        $flags[] = $flag;
      }
    }

    $basic_data = array(
      pht('Username') => $user->getUsername(),
      pht('Real name') => $user->getRealName(),
      pht('PHID') => $user->getPHID(),
      pht('Created') =>  $created_time,
      pht('Profile image') => $user->getProfileImagePHID(),
      pht('Flags') => implode(' ', $flags),
    );

    $rows = array();
    foreach ($basic_data as $field => $value) {
      $rows[] = array(
        'field' => $field,
        'value' => $value,
      );
    }
    $table_columns = array(
      'field' => array('title' => pht('Field')),
      'value' => array('title' => pht('Value')),
    );
    $table = id(new PhutilConsoleTable())
      ->setShowHeader(false)
      ->addColumns($table_columns);
    $table->drawRows($rows);
  }

}
