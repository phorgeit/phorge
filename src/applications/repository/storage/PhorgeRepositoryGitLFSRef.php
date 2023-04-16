<?php

final class PhorgeRepositoryGitLFSRef
  extends PhorgeRepositoryDAO
  implements
    PhorgePolicyInterface,
    PhorgeDestructibleInterface {

  protected $repositoryPHID;
  protected $objectHash;
  protected $byteSize;
  protected $authorPHID;
  protected $filePHID;

  protected function getConfiguration() {
    return array(
      self::CONFIG_COLUMN_SCHEMA => array(
        'objectHash' => 'bytes64',
        'byteSize' => 'uint64',
      ),
      self::CONFIG_KEY_SCHEMA => array(
        'key_hash' => array(
          'columns' => array('repositoryPHID', 'objectHash'),
          'unique' => true,
        ),
      ),
    ) + parent::getConfiguration();
  }


/* -(  PhorgePolicyInterface  )----------------------------------------- */


  public function getCapabilities() {
    return array(
      PhorgePolicyCapability::CAN_VIEW,
    );
  }

  public function getPolicy($capability) {
    return PhorgePolicies::getMostOpenPolicy();
  }

  public function hasAutomaticCapability($capability, PhorgeUser $viewer) {
    return false;
  }


/* -(  PhorgeDestructibleInterface  )----------------------------------- */


  public function destroyObjectPermanently(
    PhorgeDestructionEngine $engine) {

    $file_phid = $this->getFilePHID();

    $file = id(new PhorgeFileQuery())
      ->setViewer($engine->getViewer())
      ->withPHIDs(array($file_phid))
      ->executeOne();
    if ($file) {
      $engine->destroyObject($file);
    }

    $this->delete();
  }

}
