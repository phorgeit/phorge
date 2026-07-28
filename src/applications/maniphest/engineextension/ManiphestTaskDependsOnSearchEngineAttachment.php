<?php

final class ManiphestTaskDependsOnSearchEngineAttachment
  extends PhabricatorSearchEngineAttachment {

  public function getAttachmentName() {
    return pht('Subtasks');
  }

  public function getAttachmentDescription() {
    return pht('Get tasks on which a task depends.');
  }

  public function loadAttachmentData(array $objects, $spec) {
    $viewer = $this->getViewer();

    $objects = mpull($objects, null, 'getPHID');
    $object_phids = array_keys($objects);

    $edge_query = id(new PhabricatorEdgeQuery())
      ->withSourcePHIDs($object_phids)
      ->withEdgeTypes(
        array(
          ManiphestTaskDependsOnTaskEdgeType::EDGECONST,
        ));
    $edge_query->execute();

    $results = array();
    foreach ($objects as $phid => $object) {
      $subtask_phids = $edge_query->getDestinationPHIDs(array($phid));
      $results[$phid] = $subtask_phids;
    }

    return $results;
  }

  public function getAttachmentForObject($object, $data, $spec) {
    $subtasks = idx($data, $object->getPHID(), array());

    return array(
      'taskPHIDs' => $subtasks,
    );
  }

}
