<?php

/**
 * This is data we recorded when installing the library.
 */
final class PhorgeLibraryStoreOriginData extends PhorgeExtensionsDTO {

  protected $location;
  protected $version;
  protected $extensionKey;
  protected $storeUri;
  protected $format;

  private static $entityQuerySpec = array(
    'extensionKey' => 'string (in the form of `publisher.extension`)',
    'location' => 'string (path of dirname(__phutil_library_map__.php))',
    'version' => 'string',
    'format' => 'string (the way the extension is distributed)',
    'storeUri' => 'string (base url of the store)',
  );

  public static function fromDictionary($array) {
    // Ignore all keys we don't know about
    $array = array_select_keys($array, array_keys(self::$entityQuerySpec));
    PhutilTypeSpec::checkMap($array, self::$entityQuerySpec);

    return id(new self())
      ->setExtensionKey($array['extensionKey'])
      ->setLocation($array['location'])
      ->setVersion($array['version'])
      ->setFormat($array['format'])
      ->setStoreUri($array['storeUri']);
  }

  public function toDictionary() {
    return array(
      'extensionKey' => $this->extensionKey,
      'location' => $this->location,
      'version' => $this->version,
      'format' => $this->format,
      'storeUri' => $this->storeUri,
    );
  }

}
