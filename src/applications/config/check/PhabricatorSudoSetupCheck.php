<?php

final class PhabricatorSudoSetupCheck extends PhabricatorSetupCheck {

  public function getDefaultGroup() {
    return self::GROUP_OTHER;
  }

  protected function executeChecks() {
    $user = PhabricatorEnv::getEnvConfig('phd.user');
    if (!phutil_nonempty_string($user)) {
      return;
    }

    if (PhabricatorDaemon::isRunningAsUser($user)) {
      return;
    }

    // In a repository cluster, web nodes are not registered devices and
    // normally have no local repositories, so they never run repository
    // commands through sudo.
    if (!AlmanacKeys::getDeviceID()) {
      $cluster_services = id(new AlmanacServiceQuery())
        ->setViewer(PhabricatorUser::getOmnipotentUser())
        ->withServiceTypes(
          array(
            AlmanacClusterRepositoryServiceType::SERVICETYPE,
          ))
        ->setLimit(1)
        ->execute();
      if ($cluster_services) {
        return;
      }
    }

    $path = Filesystem::resolveBinary('sudo');
    if (!$path) {
      $this->newIssue('sudo.missing')
        ->setName(pht('Missing "sudo" Binary'))
        ->setSummary(
          pht('Repository commands need "sudo" to run as "phd.user".'))
        ->setMessage(
          pht(
            'The "phd.user" option is set to "%s", so %s runs '.
            'repository commands as that user with "sudo". No "sudo" '.
            'binary was found in PATH. Install sudo, or add its directory '.
            'to "environment.append-paths".',
            $user,
            PlatformSymbols::getPlatformServerName()))
        ->addPhabricatorConfig('phd.user')
        ->addPhabricatorConfig('environment.append-paths');
      return;
    }

    $binary = PhabricatorSudoBinary::loadForPath($path);
    $reason = $binary->getUnusableReason();
    if ($reason !== null) {
      $this->newIssue('sudo.unsupported')
        ->setName(pht('Unsupported sudo-rs Version'))
        ->setSummary(
          pht(
            'This sudo-rs release can not pass environment variables to '.
            'repository commands. Upgrade to sudo-rs 0.2.7 or greater, or '.
            'configure your server to use classic sudo.'))
        ->setMessage($reason)
        ->addPhabricatorConfig('phd.user');
    }
  }

}
