<?php

final class ExtensionQueryResult extends Phobject {

  private $extensionKey;
  private $phutilLibName;
  private $version;
  private $format;
  private $downloadUri;

  public function setExtensionKey($key) {
    $this->extensionKey = $key;
    return $this;
  }

  public function getExtensionKey() {
    return $this->extensionKey;
  }

  public function setPhutilLibName(string $name) {
    $this->phutilLibName = $name;
    return $this;
  }

  public function getPhutilLibName() {
    return $this->phutilLibName;
  }

  public function setVersion($version) {
    $this->version = $version;
    return $this;
  }

  public function getVersion() {
    return $this->version;
  }

  public function setFormat($format) {
    $this->format = $format;
    return $this;
  }

  public function getFormat() {
    return $this->format;
  }

  public function setDownloadUri($uri) {
    $this->downloadUri = $uri;
    return $this;
  }

  public function getDownloadUri() {
    return $this->downloadUri;
  }

  private static $entityQuerySpec = array(
    'extensionKey' => 'string (in the form of `publisher.extension`)',
    'phutilLibName' =>
      'string (name of the library as provided to `phutil_register_library`)',
    'version' => 'string',
    'format' => 'string (the way the extension is distributed)',
    'downloadUri' => 'string (url to download the distributed extension from)',
  );

  public static function fromDictionary($array) {
    // Ignore all keys we don't know about
    $array = array_select_keys($array, array_keys(self::$entityQuerySpec));
    PhutilTypeSpec::checkMap($array, self::$entityQuerySpec);

    $result = id(new self())
      ->setExtensionKey($array['extensionKey'])
      ->setPhutilLibName($array['phutilLibName'])
      ->setVersion($array['version'])
      ->setFormat($array['format'])
      ->setDownloadUri($array['downloadUri']);

    return $result;
  }

  public function toDictionary() {
    return array(
      'extensionKey' => $this->extensionKey,
      'phutilLibName' => $this->phutilLibName,
      'version' => $this->version,
      'format' => $this->format,
      'downloadUri' => $this->downloadUri,
    );
  }

}
