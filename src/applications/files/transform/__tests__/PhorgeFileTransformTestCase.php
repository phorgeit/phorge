<?php

final class PhorgeFileTransformTestCase extends PhorgeTestCase {

  protected function getPhorgeTestCaseConfiguration() {
    return array(
      self::PHORGE_TESTCONFIG_BUILD_STORAGE_FIXTURES => true,
    );
  }

  public function testGetAllTransforms() {
    PhorgeFileTransform::getAllTransforms();
    $this->assertTrue(true);
  }

  public function testThumbTransformDefaults() {
    $xforms = PhorgeFileTransform::getAllTransforms();
    $file = new PhorgeFile();

    foreach ($xforms as $xform) {
      if (!($xform instanceof PhorgeFileThumbnailTransform)) {
        continue;
      }

      // For thumbnails, generate the default thumbnail. This should be able
      // to generate something rather than throwing an exception because we
      // forgot to add a default file to the builtin resources. See T12614.
      $xform->getDefaultTransform($file);

      $this->assertTrue(true);
    }
  }

}
