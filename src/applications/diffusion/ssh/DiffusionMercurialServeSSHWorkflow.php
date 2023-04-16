<?php

final class DiffusionMercurialServeSSHWorkflow
  extends DiffusionMercurialSSHWorkflow {

  protected $didSeeWrite;

  protected function didConstruct() {
    $this->setName('hg');
    $this->setArguments(
      array(
        array(
          'name' => 'repository',
          'short' => 'R',
          'param' => 'repo',
        ),
        array(
          'name' => 'stdio',
        ),
        array(
          'name' => 'command',
          'wildcard' => true,
        ),
      ));
  }

  protected function identifyRepository() {
    $args = $this->getArgs();
    $path = $args->getArg('repository');
    return $this->loadRepositoryWithPath(
      $path,
      PhorgeRepositoryType::REPOSITORY_TYPE_MERCURIAL);
  }

  protected function executeRepositoryOperations() {
    $repository = $this->getRepository();
    $args = $this->getArgs();

    if (!$args->getArg('stdio')) {
      throw new Exception(pht('Expected `%s`!', 'hg ... --stdio'));
    }

    if ($args->getArg('command') !== array('serve')) {
      throw new Exception(pht('Expected `%s`!', 'hg ... serve'));
    }

    if ($this->shouldProxy()) {
      // NOTE: For now, we're always requesting a writable node. The request
      // may not actually need one, but we can't currently determine whether
      // it is read-only or not at this phase of evaluation.
      $command = $this->getProxyCommand(true);
    } else {
      $command = csprintf(
        'hg -R %s serve --stdio',
        $repository->getLocalPath());
    }
    $command = PhorgeDaemon::sudoCommandAsDaemonUser($command);

    $future = id(new ExecFuture('%C', $command))
      ->setEnv($this->getEnvironment());

    $io_channel = $this->getIOChannel();
    $protocol_channel = new DiffusionMercurialWireClientSSHProtocolChannel(
      $io_channel);

    $err = id($this->newPassthruCommand())
      ->setIOChannel($protocol_channel)
      ->setCommandChannelFromExecFuture($future)
      ->setWillWriteCallback(array($this, 'willWriteMessageCallback'))
      ->execute();

    if (!$err && $this->didSeeWrite) {
      $repository->writeStatusMessage(
        PhorgeRepositoryStatusMessage::TYPE_NEEDS_UPDATE,
        PhorgeRepositoryStatusMessage::CODE_OKAY);
    }

    return $err;
  }

  public function willWriteMessageCallback(
    PhorgeSSHPassthruCommand $command,
    $message) {

    $command = $message['command'];

    // Check if this is a readonly command.

    $is_readonly = false;
    if ($command == 'batch') {
      $cmds = idx($message['arguments'], 'cmds');
      if (DiffusionMercurialWireProtocol::isReadOnlyBatchCommand($cmds)) {
        $is_readonly = true;
      }
    } else if (DiffusionMercurialWireProtocol::isReadOnlyCommand($command)) {
      $is_readonly = true;
    }

    if (!$is_readonly) {
      $this->requireWriteAccess();
      $this->didSeeWrite = true;
    }

    return $message['raw'];
  }

  protected function raiseWrongVCSException(
    PhorgeRepository $repository) {
    throw new Exception(
      pht(
        'This repository ("%s") is not a Mercurial repository. Use "%s" to '.
        'interact with this repository.',
        $repository->getDisplayName(),
        $repository->getVersionControlSystem()));
  }

}
