<?php

final class DifferentialSetDiffPropertyConduitAPIMethod
  extends DifferentialConduitAPIMethod {

  public function getAPIMethodName() {
    return 'differential.setdiffproperty';
  }

  public function getMethodDescription() {
    return pht('Attach properties to Differential diffs.');
  }

  protected function defineParamTypes() {
    return array(
      'diff_id' => 'required diff_id',
      'name'    => 'required string',
      'data'    => 'required string',
    );
  }

  protected function defineReturnType() {
    return 'void';
  }

  protected function defineErrorTypes() {
    return array(
      'ERR_NOT_FOUND' => pht('Diff was not found.'),
      'ERR_NO_NAME' => pht('Name cannot be empty.'),
    );
  }

  protected function execute(ConduitAPIRequest $request) {
    $diff_id = $request->getValue('diff_id');
    if (!$diff_id) {
      throw new ConduitException('ERR_NOT_FOUND');
    }
    $revision = id(new DifferentialDiffQuery())
      ->setViewer($this->getViewer())
      ->withIDs(array($diff_id))
      ->requireCapabilities(
        array(
          PhabricatorPolicyCapability::CAN_VIEW,
        ))
      ->executeOne();
    if (!$revision) {
      throw new ConduitException('ERR_NOT_FOUND');
    }

    $name = $request->getValue('name');
    if (!phutil_nonempty_string($name)) {
      throw new ConduitException('ERR_NO_NAME');
    }
    $data = $request->getValue('data');
    if ($data !== null) {
      $data = json_decode($data, true);
    }
    self::updateDiffProperty($diff_id, $name, $data);
  }

  private static function updateDiffProperty($diff_id, $name, $data) {
    $property = id(new DifferentialDiffProperty())->loadOneWhere(
      'diffID = %d AND name = %s',
      $diff_id,
      $name);
    if (!$property) {
      $property = new DifferentialDiffProperty();
      $property->setDiffID($diff_id);
      $property->setName($name);
    }
    $property->setData($data);
    $property->save();
    return $property;
  }

}
