<?php

final class PhabricatorAuthManagementUnlinkWorkflow
  extends PhabricatorAuthManagementWorkflow {

  protected function didConstruct() {
    $this
      ->setName('unlink')
      ->setExamples('**unlink** --provider __provider__ --user __@user__')
      ->setSynopsis(pht('Unlink an external account from a user account.'))
      ->setArguments(
        array(
          array(
            'name' => 'user',
            'param' => 'username',
            'repeat' => false,
            'help' => pht('Unlink external account from the specified user.'),
          ),
          array(
            'name' => 'provider',
            'param' => 'providertype',
            'repeat' => false,
            'help' => pht(
              'Unlink the specific external account type.'),
          ),
          array(
            'name' => 'force',
            'help' => pht('Unlink external account without prompting.'),
          ),
          array(
            'name' => 'dry-run',
            'help' => pht('Show external account, but do not unlink it.'),
          ),
        ));
  }

  public function execute(PhutilArgumentParser $args) {
    $viewer = $this->getViewer();

    $username = $args->getArg('user');
    if (!phutil_nonempty_string($username)) {
      throw new PhutilArgumentUsageException(
        pht('Specify a username.'));
    }

    $user = id(new PhabricatorPeopleQuery())
      ->setViewer($viewer)
      ->withUsernames(array($username))
      ->executeOne();
    if (!$user) {
      throw new PhutilArgumentUsageException(
        pht(
          'No user exists with username "%s".',
          $username));
    }

    $provider = $args->getArg('provider');

    if (!phutil_nonempty_string($provider)) {
      throw new PhutilArgumentUsageException(
        pht('Specify an external auth provider.'));
    }

    $provider_map = id(new PhutilClassMapQuery())
      ->setAncestorClass('PhabricatorAuthProvider')
      ->setUniqueMethod('getProviderName')
      ->setSortMethod('getProviderName')
      ->execute();

    $config_class = null;
    foreach ($provider_map as $name => $class) {
      if ($provider === $name) {
        $config_class = $class;
        break;
      }
    }
    if ($config_class === null) {
      throw new PhutilArgumentUsageException(
        pht(
          'No auth provider exists with name "%s". Valid providers are: %s.',
          $provider,
          implode(', ', array_keys($provider_map))));
    }

    $configs = id(new PhabricatorAuthProviderConfigQuery())
      ->setViewer($viewer)
      ->withIsEnabled(true)
      ->withProviderClasses(array(get_class($config_class)))
      ->execute();
    if (!$configs) {
      throw new PhutilArgumentUsageException(
        pht(
          'No active auth provider exists with name "%s".',
          $provider));
    }

    $config_phids = mpull($configs, 'getPHID');
    $external_accounts = id(new PhabricatorExternalAccountQuery())
      ->setViewer($viewer)
      ->withUserPHIDs(array($user->getPHID()))
      ->withProviderConfigPHIDs($config_phids)
      ->execute();

    if (count($external_accounts) < 1) {
      throw new PhutilArgumentUsageException(
        pht(
          'User does not have an external account with provider "%s" '.
          'configured.',
          $provider));
    }

    if (count($external_accounts) > 1) {
      throw new PhutilArgumentUsageException(
        pht(
          'More than one external account provider of the type "%s" detected '.
          'for this user account. This is not supported by this workflow.',
          $provider));
    }

    $account = head($external_accounts);

    $handles = id(new PhabricatorHandleQuery())
      ->setViewer($this->getViewer())
      ->withPHIDs(array($account->getUserPHID()))
      ->execute();

    $console = PhutilConsole::getConsole();


    $console->writeOut("%s\n\n", pht(
      'This external account will be unlinked:'));
    $console->writeOut(
      "    %s\t%s\t%s\n",
      $username,
      $config_class->getProviderName(),
      $handles[$account->getUserPHID()]->getName());

    $is_dry_run = $args->getArg('dry-run');
    if ($is_dry_run) {
      $console->writeOut(
        "\n%s\n",
        pht('End of dry run.'));

      return 0;
    }

    $force = $args->getArg('force');
    if (!$force) {
    // TODO: Warn if this is the account's only auth, like AuthUnlinkController?
      if (!$console->confirm(pht('Unlink this external account?'))) {
        throw new PhutilArgumentUsageException(
          pht('User aborted the workflow.'));
      }
    }

    $console->writeOut("%s\n", pht('Unlinking external account...'));

    $account->unlinkAccount();

    $console->writeOut("%s\n", pht('Done.'));

    return 0;
  }

}
