<?php

final class PhorgeFileExternalRequest extends PhorgeFileDAO
  implements
    PhorgeDestructibleInterface {

  protected $uri;
  protected $uriIndex;
  protected $ttl;
  protected $filePHID;
  protected $isSuccessful;
  protected $responseMessage;

  protected function getConfiguration() {
    return array(
      self::CONFIG_COLUMN_SCHEMA => array(
        'uri' => 'text',
        'uriIndex' => 'bytes12',
        'ttl' => 'epoch',
        'filePHID' => 'phid?',
        'isSuccessful' => 'bool',
        'responseMessage' => 'text?',
      ),
      self::CONFIG_KEY_SCHEMA => array(
        'key_uriindex' => array(
          'columns' => array('uriIndex'),
          'unique' => true,
        ),
        'key_ttl' => array(
          'columns' => array('ttl'),
        ),
        'key_file' => array(
          'columns' => array('filePHID'),
        ),
      ),
    ) + parent::getConfiguration();
  }

  public function save() {
    $hash = PhorgeHash::digestForIndex($this->getURI());
    $this->setURIIndex($hash);
    return parent::save();
  }

/* -(  PhorgeDestructibleInterface  )----------------------------------- */

  public function destroyObjectPermanently(
    PhorgeDestructionEngine $engine) {

    $file_phid = $this->getFilePHID();
    if ($file_phid) {
      $file = id(new PhorgeFileQuery())
        ->setViewer($engine->getViewer())
        ->withPHIDs(array($file_phid))
        ->executeOne();
      if ($file) {
        $engine->destroyObject($file);
      }
    }
    $this->delete();
  }

}
