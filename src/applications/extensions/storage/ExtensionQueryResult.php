<?php

/**
 * This is the wire protocol of conduit method `extensionstore.queryextension`.
 */
final class ExtensionQueryResult extends PhorgeExtensionsDTO {

  protected $extensionKey;
  protected $phutilLibName;
  protected $version;
  protected $format;
  protected $downloadUri;
  protected $storeUri;

  private static $entityQuerySpec = array(
    'extensionKey' => 'string (in the form of `publisher.extension`)',
    'phutilLibName' =>
      'string (name of the library as provided to `phutil_register_library`)',
    'version' => 'string',
    'format' => 'string (the way the extension is distributed)',
    'downloadUri' => 'string (url to download the distributed extension from)',
    'storeUri' => 'string (base url of the store)',
  );

  public static function fromDictionary($array) {
    // Ignore all keys we don't know about
    $array = array_select_keys($array, array_keys(self::$entityQuerySpec));
    PhutilTypeSpec::checkMap($array, self::$entityQuerySpec);

    return id(new self())
      ->setExtensionKey($array['extensionKey'])
      ->setPhutilLibName($array['phutilLibName'])
      ->setVersion($array['version'])
      ->setFormat($array['format'])
      ->setDownloadUri($array['downloadUri'])
      ->setStoreUri($array['storeUri']);
  }

  public function toDictionary() {
    return array(
      'extensionKey' => $this->extensionKey,
      'phutilLibName' => $this->phutilLibName,
      'version' => $this->version,
      'format' => $this->format,
      'downloadUri' => $this->downloadUri,
      'storeUri' => $this->storeUri,
    );
  }

}
