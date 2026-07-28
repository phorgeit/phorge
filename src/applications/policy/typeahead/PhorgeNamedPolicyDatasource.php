<?php

final class PhorgeNamedPolicyDatasource
  extends PhabricatorTypeaheadDatasource {

  public function getBrowseTitle() {
    return pht('Browse Named Policies');
  }

  public function getPlaceholderText() {
    return pht('Type Named Policy\'s name...');
  }

  public function getDatasourceApplicationClass() {
    return PhabricatorPolicyApplication::class;
  }

  public function loadResults() {
    $viewer = $this->getViewer();

    $query = id(new PhorgeNamedPolicyQuery())
      ->setViewer($viewer);

    $object_type = $this->getParameter('targetObjectType');
    if ($object_type) {
      $phid_types = PhabricatorPHIDType::getAllInstalledTypes($viewer);
      $object = null;
      if (!empty($phid_types[$object_type])) {
        $object = $phid_types[$object_type]->newObject();
      }

      if ($object instanceof PhabricatorPolicyInterface) {
        $query->withCanApplyToObject($object);
      }
    }

    $objects = $query->execute();

    $results = array();
    foreach ($objects as $policy) {

      $results[] = id(new PhabricatorTypeaheadResult())
        ->setName($policy->getName())
        ->addAttribute(pht('Named Policy'))
        ->setPHID($policy->getPHID());
    }

    $results = $this->filterResultsAgainstTokens($results);

    return $results;
  }

}
