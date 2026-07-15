<?php

abstract class PhorgeTransactionManagementWorkflow
  extends PhabricatorManagementWorkflow {

  protected function transactionQueryForObject($object_phid_type):
    PhabricatorApplicationTransactionQuery {

    $queries = id(new PhutilClassMapQuery())
      ->setAncestorClass(PhabricatorApplicationTransactionQuery::class)
      ->execute();

    foreach ($queries as $query) {
      $query_phid_type = $query->getTemplateApplicationTransaction()
        ->getApplicationTransactionType();
      if ($query_phid_type == $object_phid_type) {
        return $query;
      }
    }

    throw new ArcanistUsageException(
      pht(
        'No transaction quey implementation matches phid type "%s"',
        $object_phid_type));
  }

  protected function resolveObjectPHID($object_name) {
    $object_phid_type = phid_get_type($object_name);

    if ($object_phid_type != PhabricatorPHIDConstants::PHID_TYPE_UNKNOWN) {
      return $object_name;
    }

    // probably not actually a phid
    $object = id(new PhabricatorObjectQuery())
      ->setViewer($this->getViewer())
      ->withNames(array($object_name))
      ->executeOne();

    if (!$object) {
      throw new PhutilArgumentUsageException(
        pht('Object "%s" was not found', $object_name));
    }
    return $object->getPHID();
  }

  /**
   * Returns an array that can be json-encoded.
   */
  protected function transactionData(
    PhabricatorApplicationTransaction $xaction): array {

    $data = array(
        'phid'  => $xaction->getPHID(),
        'class' => get_class($xaction),
        'object' => $xaction->getObjectPHID(),
        'author' => $xaction->getAuthorPHID(),
        'oldValue' => $xaction->getOldValue(),
        'epoch' => $xaction->getDateCreated(),
        'type' => $xaction->getTransactionType(),
        'newValue' => $xaction->getNewValue(),
        'metadata' => $xaction->getMetadata(),
        'title' => (string)$xaction->getTitleForTextMail(),
      );

    return $data;
  }

}
