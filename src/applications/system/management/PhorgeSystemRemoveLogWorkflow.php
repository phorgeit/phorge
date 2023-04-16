<?php

final class PhorgeSystemRemoveLogWorkflow
  extends PhorgeSystemRemoveWorkflow {

  protected function didConstruct() {
    $this
      ->setName('log')
      ->setSynopsis(pht('Show a log of permanently destroyed objects.'))
      ->setExamples('**log**')
      ->setArguments(array());
  }

  public function execute(PhutilArgumentParser $args) {
    $console = PhutilConsole::getConsole();

    $table = new PhorgeSystemDestructionLog();
    foreach (new LiskMigrationIterator($table) as $row) {
      $console->writeOut(
        "[%s]\t%s %s\t%s\t%s\n",
        phorge_datetime($row->getEpoch(), $this->getViewer()),
        ($row->getRootLogID() ? ' ' : '*'),
        $row->getObjectClass(),
        $row->getObjectPHID(),
        $row->getObjectMonogram());
    }

    return 0;
  }

}
