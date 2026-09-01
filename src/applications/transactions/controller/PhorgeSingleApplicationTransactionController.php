<?php

abstract class PhorgeSingleApplicationTransactionController
  extends PhabricatorApplicationTransactionController {

  final public function handleRequest(AphrontRequest $request) {
    $viewer = $this->getViewer();

    $xaction = id(new PhabricatorObjectQuery())
      ->setViewer($viewer)
      ->withPHIDs(array($request->getURIData('phid')))
      ->withTypes(array(
        PhabricatorApplicationTransactionTransactionPHIDType::TYPECONST,
      ))
      ->executeOne();

    if (!$xaction) {
      return new Aphront404Response();
    }

    return $this->handleTransaction($xaction);
  }

  abstract protected function handleTransaction(
    PhabricatorApplicationTransaction $xaction);

}
