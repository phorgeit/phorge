<?php

final class PhabricatorRepositoriesSetupCheck extends PhabricatorSetupCheck {

  public function getDefaultGroup() {
    return self::GROUP_OTHER;
  }

  protected function executeChecks() {

    // Check for deprecated "Track Only" feature - https://we.phorge.it/T16603
    $repository = id(new PhabricatorRepositoryQuery())
      ->setViewer(PhabricatorUser::getOmnipotentUser())
      ->execute();

    $track_only_repos = array();

    foreach ($repository as $repo) {
      if ($repo->getTrackOnlyRules() !== array()) {
        $track_only_repos[] = array(
          'name' => $repo->getName(),
          'id' => $repo->getID(),
        );
      }
    }

    if ($track_only_repos) {
      $update = array();
      foreach ($track_only_repos as $repo) {
        $link = phutil_tag(
          'a',
          array(
            'href' => '/diffusion/edit/'.$repo['id'].'/page/branches/',
            'target' => '_blank',
          ),
          $repo['name']);
        $update[] = phutil_tag('li', array(), $link);
      }
      $update = phutil_tag('ul', array(), $update);

      $summary = pht(
        'Repositories with "Track Only" branches require updating.');
      $message = pht(
        'Some repositories are configured to use the deprecated '.
        '"Track Only" feature. This feature has been deprecated since 2019 '.
        'and will be removed in a future version of this software. '.
        "\n\n".
        'Edit the repositories to remove the "Track Only" setting and use '.
        'the "Fetch Refs" and "Permanent Refs" features instead:'.
        '%s'.
        'To learn more about repository branch configuration, see %s.',
        $update,
        phutil_tag(
          'a',
          array(
            'href' => PhabricatorEnv::getDoclink(
              'Diffusion User Guide: Managing Repositories'),
            'target' => '_blank',
          ),
          pht('Diffusion User Guide: Managing Repositories')));

      $this
        ->newIssue('diffusion.track-only-deprecation')
        ->setName(pht('Repositories with "Track Only" branches'))
        ->setSummary($summary)
        ->setMessage($message);
    }


    $cluster_services = id(new AlmanacServiceQuery())
      ->setViewer(PhabricatorUser::getOmnipotentUser())
      ->withServiceTypes(
        array(
          AlmanacClusterRepositoryServiceType::SERVICETYPE,
        ))
      ->setLimit(1)
      ->execute();
    if ($cluster_services) {
      // If cluster repository services are defined, these checks aren't useful
      // because some nodes (like web nodes) will usually not have any local
      // repository information.

      // Errors with this configuration will still be detected by checks on
      // individual repositories.
      return;
    }

    $repo_path = PhabricatorEnv::getEnvConfig('repository.default-local-path');

    if (!$repo_path) {
      $summary = pht(
        "The configuration option '%s' is not set.",
        'repository.default-local-path');
      $this->newIssue('repository.default-local-path.empty')
        ->setName(pht('Missing Repository Local Path'))
        ->setSummary($summary)
        ->addPhabricatorConfig('repository.default-local-path');
      return;
    }

    if (!Filesystem::pathExists($repo_path)) {
      $summary = pht(
        'The path for local repositories does not exist, or is not '.
        'readable by the webserver.');
      $message = pht(
        "The directory for local repositories (%s) does not exist, or is not ".
        "readable by the webserver. This software uses this directory to ".
        "store information about repositories. If this directory does not ".
        "exist, create it:\n\n".
        "%s\n".
        "If this directory exists, make it readable to the webserver. You ".
        "can also edit the configuration below to use some other directory.",
        phutil_tag('tt', array(), $repo_path),
        phutil_tag('pre', array(), csprintf('$ mkdir -p %s', $repo_path)));

      $this->newIssue('repository.default-local-path.empty')
        ->setName(pht('Missing Repository Local Path'))
        ->setSummary($summary)
        ->setMessage($message)
        ->addPhabricatorConfig('repository.default-local-path');
    }


    // Check for deprecated Herald conditions - https://we.phorge.it/T16733
    $rules = id(new HeraldRuleQuery())
      ->setViewer(PhabricatorUser::getOmnipotentUser())
      ->withContentTypes(array('commit'))
      ->needConditionsAndActions(true)
      ->needValidateAuthors(false)
      ->execute();

    $deprecated_rules = array();
    $autoclose = DiffusionCommitAutocloseHeraldField::FIELDCONST;
    $reviewer = DiffusionCommitReviewerHeraldField::FIELDCONST;

    foreach ($rules as $rule) {
      $conditions = $rule->loadConditions();
      foreach ($conditions as $cond) {
        if ($cond->getFieldName() === $autoclose ||
            $cond->getFieldName() === $reviewer) {
          $deprecated_rules[] = array(
            'id' => $rule->getID(),
            'monogram' => $rule->getMonogram(),
            'name' => $rule->getName(),
          );
          continue;
        }
      }
    }

    if ($deprecated_rules) {
      $update = array();
      foreach ($deprecated_rules as $rule) {
        $link = phutil_tag(
          'a',
          array(
            'href' => '/herald/edit/'.$rule['id'].'/',
            'target' => '_blank',
          ),
          $rule['monogram'].': '.$rule['name']);
        $update[] = phutil_tag('li', array(), $link);
      }
      $update = phutil_tag('ul', array(), $update);

      $summary = pht(
        'Herald Rules with deprecated conditions require updating.');
      $message = pht(
        'Some Herald rules for commits use the deprecated "Commit Autocloses" '.
        'or "Reviewer" conditions. These conditions have been deprecated '.
        'since 2019 and will be removed in a future version of this '.
        'software.'.
        "\n\n".
        'Edit the rules to remove all deprecated conditions.'.
        "\n".
        'Remove any "Commit Autocloses" conditions; they have no effect '.
        'anymore.'.
        "\n".
        'Replace any "Reviewer" conditions with "Accepting Reviewers".'.
        '%s'.
        'To learn more about Herald rules, see %s.',
        $update,
        phutil_tag(
          'a',
          array(
            'href' => PhabricatorEnv::getDoclink(
              'Herald User Guide'),
            'target' => '_blank',
          ),
          pht('Herald User Guide')));

      $this
        ->newIssue('herald.diffusion-deprecation')
        ->setName(pht('Herald Rules With Deprecated Conditions'))
        ->setSummary($summary)
        ->setMessage($message);
    }

  }

}
