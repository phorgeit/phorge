<?php

final class PhrictionHistoryConduitAPIMethod extends PhrictionConduitAPIMethod {

  public function getAPIMethodName() {
    return 'phriction.history';
  }

  public function getMethodDescription() {
    return pht('Retrieve history about a Phriction document.');
  }

  public function getMethodStatus() {
    return self::METHOD_STATUS_DEPRECATED;
  }

  public function getMethodStatusDescription() {
    return pht(
      'This method has been deprecated since %s in favor of %s.',
      '04/2026',
      'phriction.content.search');
  }

  protected function defineParamTypes() {
    return array(
      'slug' => 'required string',
    );
  }

  protected function defineReturnType() {
    return 'nonempty list';
  }

  protected function defineErrorTypes() {
    return array(
      'ERR-BAD-DOCUMENT' => pht('No such document exists.'),
    );
  }

  protected function execute(ConduitAPIRequest $request) {
    $slug = $request->getValue('slug');
    $document = null;

    if ($slug !== null) {
      $document = id(new PhrictionDocumentQuery())
        ->setViewer($request->getUser())
        ->withSlugs(array(PhabricatorSlug::normalize($slug)))
        ->executeOne();
    }
    if (!$document) {
      throw new ConduitException('ERR-BAD-DOCUMENT');
    }

    $content = id(new PhrictionContent())->loadAllWhere(
      'documentPHID = %s ORDER BY version DESC',
      $document->getPHID());

    $results = array();
    foreach ($content as $version) {
      $results[] = $this->buildDocumentContentDictionary(
        $document,
        $version);
    }

    return $results;
  }

}
