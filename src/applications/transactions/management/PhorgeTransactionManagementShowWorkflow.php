<?php

final class PhorgeTransactionManagementShowWorkflow
  extends PhorgeTransactionManagementWorkflow {

  protected function didConstruct() {
    $this
      ->setName('show')
      ->setSynopsis(pht('Detailed view of a transaction.'))
      ->setExamples('**show** PHID-XACT-TASK-ai5bqk6fqf7cj5d')
      ->setArguments(
        array(
          id(new PhutilArgumentSpecification())
            ->setName('xaction')
            ->setHelp(pht('Transaction PHID to show.'))
            ->setWildcard(true),

        ));
  }

  public function execute(PhutilArgumentParser $args) {
    $viewer = $this->getViewer();

    $xaction_phids = $args->getArg('xaction');

    if (!$xaction_phids) {
      throw new PhutilArgumentUsageException(
        pht('Specify transaction PHID to show.')
      );
    }

    /** @var array<PhabricatorApplicationTransaction> */
    $xactions = id(new PhabricatorObjectQuery())
      ->setViewer($viewer)
      ->withPHIDs($xaction_phids)
      ->withTypes(
        array(PhabricatorApplicationTransactionTransactionPHIDType::TYPECONST))
      ->execute();

    if (!$xactions) {
      throw new PhutilArgumentUsageException(
        pht('No transactions found.'));
    }

    $rows = array();

    foreach ($xactions as $xaction) {
      $phid = $xaction->getPHID();

      $rows[$phid] = $this->transactionData($xaction);
    }



    $json = new PhutilJSON();
    PhutilConsole::getConsole()
      ->writeOut($json->encodeFormatted($rows));
  }

}
