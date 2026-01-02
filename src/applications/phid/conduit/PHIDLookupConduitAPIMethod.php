<?php

final class PHIDLookupConduitAPIMethod extends PHIDConduitAPIMethod {

  public function getAPIMethodName() {
    return 'phid.lookup';
  }

  public function getMethodDescription() {
    return pht('Look up objects by name.');
  }

  protected function defineParamTypes() {
    return array(
      'names' => 'required list<string>',
    );
  }

  protected function defineReturnType() {
    return 'nonempty dict<string, wild>';
  }

  protected function defineErrorTypes() {
    return array(
      'ERR-INVALID-PARAMETER' => pht('Must pass names.'),
    );
  }

  protected function execute(ConduitAPIRequest $request) {
    $names = $request->getValue('names');

    if (!$names) {
      throw new ConduitException('ERR-INVALID-PARAMETER');
    }

    $query = id(new PhabricatorObjectQuery())
      ->setViewer($request->getUser())
      ->withNames($names);
    $query->execute();
    $name_map = $query->getNamedResults();

    $handles = id(new PhabricatorHandleQuery())
      ->setViewer($request->getUser())
      ->withPHIDs(mpull($name_map, 'getPHID'))
      ->execute();

    $result = array();
    foreach ($name_map as $name => $object) {
      $phid = $object->getPHID();
      $handle = $handles[$phid];
      $result[$name] = $this->buildHandleInformationDictionary($handle);
    }

    return $result;
  }

}
