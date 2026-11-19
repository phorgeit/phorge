<?php

final class FileQueryChunksConduitAPIMethod
  extends FileConduitAPIMethod {

  public function getAPIMethodName() {
    return 'file.querychunks';
  }

  public function getMethodDescription() {
    return pht('Get information about file chunks.');
  }

  protected function defineParamTypes() {
    return array(
      'filePHID' => 'phid',
    );
  }

  protected function defineReturnType() {
    return 'list<wild>';
  }

  protected function defineErrorTypes() {
    return array(
      'ERR-BAD-PHID' => pht('Must pass a PHID.'),
    );
  }

  protected function execute(ConduitAPIRequest $request) {
    $viewer = $request->getViewer();

    $file_phid = $request->getValue('filePHID');
    if (!$file_phid) {
      throw new ConduitException('ERR-BAD-PHID');
    }

    $file = id(new PhabricatorFileQuery())
      ->setViewer($viewer)
      ->withPHIDs(array($file_phid))
      ->executeOne();
    if (!$file) {
      throw id(new ConduitException('ERR-BAD-PHID'))
        ->setErrorDescription(pht('No such file "%s"!', $file_phid));
    }

    $chunks = $this->loadFileChunks($viewer, $file);

    $results = array();
    foreach ($chunks as $chunk) {
      $results[] = array(
        'byteStart' => $chunk->getByteStart(),
        'byteEnd' => $chunk->getByteEnd(),
        'complete' => (bool)$chunk->getDataFilePHID(),
      );
    }

    return $results;
  }

}
