<?php

final class PhabricatorJavelinLinterTestCase extends ArcanistLinterTestCase {

  protected function getLinter() {
    $linter = id(new PhabricatorJavelinLinter())
      ->enableUnitTestOverrides(true);

    $phorge_root = Filesystem::resolvePath(
      phutil_get_library_root('phorge').'/../').'/';
    $webroot = 'webroot/rsrc/externals/javelin/';

    $files = id(new FileFinder($phorge_root.$webroot))
      ->excludePath('*/__tests__/*')
      ->excludePath('*/docs/*')
      ->withSuffix('js')
      ->find();

    foreach ($files as $file) {
      $data = Filesystem::readFile($phorge_root.$webroot.$file);
      $linter->addData(
        $webroot.$file,
        $data);
    }

    return $linter;
  }

  public function testLinter() {
    $this->executeTestsInDirectory(__DIR__.'/javelin/');
  }

}
