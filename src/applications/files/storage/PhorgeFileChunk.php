<?php

final class PhorgeFileChunk extends PhorgeFileDAO
  implements
    PhorgePolicyInterface,
    PhorgeDestructibleInterface {

  protected $chunkHandle;
  protected $byteStart;
  protected $byteEnd;
  protected $dataFilePHID;

  private $dataFile = self::ATTACHABLE;

  protected function getConfiguration() {
    return array(
      self::CONFIG_TIMESTAMPS => false,
      self::CONFIG_COLUMN_SCHEMA => array(
        'chunkHandle' => 'bytes12',
        'byteStart' => 'uint64',
        'byteEnd' => 'uint64',
        'dataFilePHID' => 'phid?',
      ),
      self::CONFIG_KEY_SCHEMA => array(
        'key_file' => array(
          'columns' => array('chunkHandle', 'byteStart', 'byteEnd'),
        ),
        'key_data' => array(
          'columns' => array('dataFilePHID'),
        ),
      ),
    ) + parent::getConfiguration();
  }

  public static function newChunkHandle() {
    $seed = Filesystem::readRandomBytes(64);
    return PhorgeHash::digestForIndex($seed);
  }

  public static function initializeNewChunk($handle, $start, $end) {
    return id(new PhorgeFileChunk())
      ->setChunkHandle($handle)
      ->setByteStart($start)
      ->setByteEnd($end);
  }

  public function attachDataFile(PhorgeFile $file = null) {
    $this->dataFile = $file;
    return $this;
  }

  public function getDataFile() {
    return $this->assertAttached($this->dataFile);
  }


/* -(  PhorgePolicyInterface  )----------------------------------------- */


  public function getCapabilities() {
    return array(
      PhorgePolicyCapability::CAN_VIEW,
    );
  }


  public function getPolicy($capability) {
    // These objects are low-level and only accessed through the storage
    // engine, so policies are mostly just in place to let us use the common
    // query infrastructure.
    return PhorgePolicies::getMostOpenPolicy();
  }


  public function hasAutomaticCapability($capability, PhorgeUser $viewer) {
    return false;
  }


/* -(  PhorgeDestructibleInterface  )----------------------------------- */


  public function destroyObjectPermanently(
    PhorgeDestructionEngine $engine) {

    $data_phid = $this->getDataFilePHID();
    if ($data_phid) {
      $data_file = id(new PhorgeFileQuery())
        ->setViewer($engine->getViewer())
        ->withPHIDs(array($data_phid))
        ->executeOne();
      if ($data_file) {
        $engine->destroyObject($data_file);
      }
    }

    $this->delete();
  }

}
