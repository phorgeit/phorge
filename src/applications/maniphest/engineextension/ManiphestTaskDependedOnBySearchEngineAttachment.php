<?php

final class ManiphestTaskDependedOnBySearchEngineAttachment
  extends PhabricatorSearchEngineAttachment {

  public function getAttachmentName() {
    return pht('Parent tasks');
  }

  public function getAttachmentDescription() {
    return pht('Get tasks which depend on a task.');
  }

  public function loadAttachmentData(array $objects, $spec) {
    $viewer = $this->getViewer();

    $objects = mpull($objects, null, 'getPHID');
    $object_phids = array_keys($objects);

    $edge_query = id(new PhabricatorEdgeQuery())
      ->withSourcePHIDs($object_phids)
      ->withEdgeTypes(
        array(
          ManiphestTaskDependedOnByTaskEdgeType::EDGECONST,
        ));
    $edge_query->execute();

    $results = array();
    foreach ($objects as $phid => $object) {
      $parent_task_phids = $edge_query->getDestinationPHIDs(array($phid));
      $results[$phid] = $parent_task_phids;
    }

    return $results;
  }

  public function getAttachmentForObject($object, $data, $spec) {
    $parent_tasks = idx($data, $object->getPHID(), array());

    return array(
      'taskPHIDs' => $parent_tasks,
    );
  }

}
