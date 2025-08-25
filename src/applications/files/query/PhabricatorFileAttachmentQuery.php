<?php

/**
 * @extends PhabricatorCursorPagedPolicyAwareQuery<PhabricatorFileAttachment>
 */
final class PhabricatorFileAttachmentQuery
  extends PhabricatorCursorPagedPolicyAwareQuery {

  private $objectPHIDs;
  private $objectPHIDPrefix;
  private $filePHIDs;
  private $needFiles;
  private $visibleFiles;
  private $attachmentModes;

  /**
   * Filter with these object PHIDs.
   *
   * @param array<string> $object_phids Example: array('PHID-USER-123abc')
   * @return $this
   */
  public function withObjectPHIDs(array $object_phids) {
    $this->objectPHIDs = $object_phids;
    return $this;
  }

  /**
   * Filter with a PHID object type.
   *
   * This is just syntax sugar for the method withObjectPHIDPrefix(),
   * so you can pass constants like PhabricatorPeopleUserPHIDType::TYPECONST.
   *
   * @param string $phid_type PHID type constant. Example: 'USER'.
   * @return $this
   */
  public function withObjectPHIDType(string $phid_type) {
    return $this->withObjectPHIDPrefix("PHID-{$phid_type}-");
  }

  /**
   * Filter with a object PHID prefix string.
   *
   * @param string $phid_prefix PHID prefix. Example: 'PHID-USER-'
   * @return $this
   */
  public function withObjectPHIDPrefix(string $phid_prefix) {
    $this->objectPHIDPrefix = $phid_prefix;
    return $this;
  }

  /**
   * @param array<string> $file_phids Array of file PHIDs.
   * @return $this
   */
  public function withFilePHIDs(array $file_phids) {
    $this->filePHIDs = $file_phids;
    return $this;
  }

  /**
   * If the files must be visible by the current viewer.
   *
   * @param bool $visible_files
   * @return $this
   */
  public function withVisibleFiles($visible_files) {
    $this->visibleFiles = $visible_files;
    return $this;
  }

  /**
   * Filter with some attachment modes.
   *
   * @param array<string> $attachment_modes Array of attachment modes defined
   * in the in the PhabricatorFileAttachment class.
   * Example: 'array('attach','reference')'.
   * @return $this
   */
  public function withAttachmentModes(array $attachment_modes) {
    $this->attachmentModes = $attachment_modes;
    return $this;
  }

  /**
   * If you also need the file objects.
   *
   * @param bool $need True if you also need the file objects.
   * @return $this
   */
  public function needFiles($need) {
    $this->needFiles = $need;
    return $this;
  }

  public function newResultObject() {
    return new PhabricatorFileAttachment();
  }

  protected function buildWhereClauseParts(AphrontDatabaseConnection $conn) {
    $where = parent::buildWhereClauseParts($conn);

    if ($this->objectPHIDs !== null) {
      $where[] = qsprintf(
        $conn,
        'attachments.objectPHID IN (%Ls)',
        $this->objectPHIDs);
    }

    if ($this->objectPHIDPrefix !== null) {
      $where[] = qsprintf(
        $conn,
        'attachments.objectPHID LIKE %>',
        $this->objectPHIDPrefix);
    }

    if ($this->filePHIDs !== null) {
      $where[] = qsprintf(
        $conn,
        'attachments.filePHID IN (%Ls)',
        $this->filePHIDs);
    }

    if ($this->attachmentModes !== null) {
      $where[] = qsprintf(
        $conn,
        'attachments.attachmentMode IN (%Ls)',
        $this->attachmentModes);
    }

    return $where;
  }

  protected function willFilterPage(array $attachments) {
    $viewer = $this->getViewer();
    $object_phids = array();

    foreach ($attachments as $attachment) {
      $object_phid = $attachment->getObjectPHID();
      $object_phids[$object_phid] = $object_phid;
    }

    if ($object_phids) {
      $objects = id(new PhabricatorObjectQuery())
        ->setViewer($viewer)
        ->setParentQuery($this)
        ->withPHIDs($object_phids)
        ->execute();
      $objects = mpull($objects, null, 'getPHID');
    } else {
      $objects = array();
    }

    foreach ($attachments as $key => $attachment) {
      $object_phid = $attachment->getObjectPHID();
      $object = idx($objects, $object_phid);

      if (!$object) {
        $this->didRejectResult($attachment);
        unset($attachments[$key]);
        continue;
      }

      $attachment->attachObject($object);
    }

    if ($this->needFiles) {
      $file_phids = array();
      foreach ($attachments as $attachment) {
        $file_phid = $attachment->getFilePHID();
        $file_phids[$file_phid] = $file_phid;
      }

      if ($file_phids) {
        $files = id(new PhabricatorFileQuery())
          ->setViewer($viewer)
          ->setParentQuery($this)
          ->withPHIDs($file_phids)
          ->execute();
        $files = mpull($files, null, 'getPHID');
      } else {
        $files = array();
      }

      foreach ($attachments as $key => $attachment) {
        $file_phid = $attachment->getFilePHID();
        $file = idx($files, $file_phid);

        if ($this->visibleFiles && !$file) {
          $this->didRejectResult($attachment);
          unset($attachments[$key]);
          continue;
        }

        $attachment->attachFile($file);
      }
    }

    return $attachments;
  }

  protected function getPrimaryTableAlias() {
    return 'attachments';
  }

  public function getQueryApplicationClass() {
    return PhabricatorFilesApplication::class;
  }

}
