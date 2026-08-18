<?php

final class PhorgeExtensionsLocalData extends Phobject {

  private static $filename = 'extensions-database.json';

  private $data;


  public function setRecordInMap($key, $entry_key, $value) {
    $map = idx($this->data, $key);
    if ($map === null) {
      $map = array();
    }
    assert(is_array($map));

    $map[$entry_key] = $value;
    $this->data[$key] = $map;
  }

  public function findRecordInMap($key, $entry_key) {
    $map = idx($this->data, $key);
    if (!$map) {
      return null;
    }

    return idx($map, $entry_key);
  }

  public function removeRecordFromMap($key, $entry_key) {
    $map = idx($this->data, $key);
    if (!$map) {
      return;
    }

    unset($map[$entry_key]);
    $this->data[$key] = $map;
  }

  public static function load() {
    $obj = new self();
    $obj->data = array();

    if (Filesystem::pathExists(self::filename())) {
      $read = Filesystem::readFile(self::filename());
      if (phutil_nonempty_string($read)) {
        $obj->data = phutil_json_decode($read);
      }
    }

    return $obj;
  }

  public function save() {
    $serialized = id(new PhutilJSON())->encodeFormatted($this->data);
    Filesystem::writeFile(self::filename(), $serialized);
  }

  private static function filename() {
    return Filesystem::resolvePath(
      self::$filename,
      PhabricatorEnv::getEnvConfig('extensions.install-dir'));
  }

}
