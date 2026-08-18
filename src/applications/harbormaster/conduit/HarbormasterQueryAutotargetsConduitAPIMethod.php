<?php

final class HarbormasterQueryAutotargetsConduitAPIMethod
  extends HarbormasterConduitAPIMethod {

  public function getAPIMethodName() {
    return 'harbormaster.queryautotargets';
  }

  public function getMethodDescription() {
    return pht('Load or create build autotargets.');
  }

  public function isReadOnlyAPI() {
    return true;
  }

  protected function defineParamTypes() {
    return array(
      'objectPHID' => 'phid',
      'targetKeys' => 'list<string>',
    );
  }

  protected function defineReturnType() {
    return 'map<string, phid>';
  }

  protected function defineErrorTypes() {
    return array(
      'ERR-BAD-OBJECTPHID' => pht(
        'No object exists with this "%s".',
        'objectPHID'),
      'ERR-NO-BUILDABLE-OBJECT' => pht(
        'Object with this "%s" does not implement interface "%s". '.
        'Autotargets may only be queried for buildable objects.',
        'objectPHID',
        HarbormasterBuildableInterface::class),
    );
  }

  protected function execute(ConduitAPIRequest $request) {
    $viewer = $request->getUser();

    $phid = $request->getValue('objectPHID');

    // NOTE: We use withNames() to let monograms like "D123" work, which makes
    // this a little easier to test. Real PHIDs will still work as expected.

    $object = id(new PhabricatorObjectQuery())
      ->setViewer($viewer)
      ->withNames(array($phid))
      ->executeOne();
    if (!$object) {
      throw new ConduitException('ERR-BAD-OBJECTPHID');
    }

    if (!($object instanceof HarbormasterBuildableInterface)) {
      throw new ConduitException('ERR-NO-BUILDABLE-OBJECT');
    }

    $autotargets = $request->getValue('targetKeys', array());

    if ($autotargets) {
      $targets = id(new HarbormasterTargetEngine())
        ->setViewer($viewer)
        ->setObject($object)
        ->setAutoTargetKeys($autotargets)
        ->buildTargets();
    } else {
      $targets = array();
    }

    // Reorder the results according to the request order so we can make test
    // assertions that subsequent calls return the same results.

    $map = mpull($targets, 'getPHID');
    $map = array_select_keys($map, $autotargets);

    return array(
      'targetMap' => $map,
    );
  }

}
