<?php

final class DrydockSSHCommandInterface extends DrydockCommandInterface {

  private $credential;
  private $connectTimeout;

  private function loadCredential() {
    if ($this->credential === null) {
      $credential_phid = $this->getConfig('credentialPHID');

      $this->credential = PassphraseSSHKey::loadFromPHID(
        $credential_phid,
        PhabricatorUser::getOmnipotentUser());
    }

    return $this->credential;
  }

  public function setConnectTimeout($timeout) {
    $this->connectTimeout = $timeout;
    return $this;
  }

  public function getExecFuture($command) {
    $credential = $this->loadCredential();

    $argv = func_get_args();
    $argv = $this->applyWorkingDirectoryToArgv($argv);
    $full_command = call_user_func_array('csprintf', $argv);

    $flags = array();

    // See T13121. Attempt to suppress the "Permanently added X to list of
    // known hosts" message without suppressing anything important.
    $flags[] = '-o';
    $flags[] = 'LogLevel=ERROR';

    $flags[] = '-o';
    $flags[] = 'StrictHostKeyChecking=no';

    $flags[] = '-o';
    $flags[] = 'UserKnownHostsFile=/dev/null';

    $flags[] = '-o';
    $flags[] = 'BatchMode=yes';

    if ($this->connectTimeout) {
      $flags[] = '-o';
      $flags[] = 'ConnectTimeout='.$this->connectTimeout;
    }

    // NOTE: Put "--" before the host so an address beginning with "-" can not
    // be read as an "ssh" option. See T16852.
    return new ExecFuture(
      'ssh %Ls -l %P -p %s -i %P -- %s %s',
      $flags,
      $credential->getUsernameEnvelope(),
      $this->getConfig('port'),
      $credential->getKeyfileEnvelope(),
      $this->getConfig('host'),
      $full_command);
  }
}
