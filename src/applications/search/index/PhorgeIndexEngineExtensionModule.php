<?php

final class PhorgeIndexEngineExtensionModule
  extends PhorgeConfigModule {

  public function getModuleKey() {
    return 'indexengine';
  }

  public function getModuleName() {
    return pht('Engine: Index');
  }

  public function renderModuleStatus(AphrontRequest $request) {
    $viewer = $request->getViewer();

    $extensions = PhorgeIndexEngineExtension::getAllExtensions();

    $rows = array();
    foreach ($extensions as $extension) {
      $rows[] = array(
        get_class($extension),
        $extension->getExtensionName(),
      );
    }

    return id(new AphrontTableView($rows))
      ->setHeaders(
        array(
          pht('Class'),
          pht('Name'),
        ))
      ->setColumnClasses(
        array(
          null,
          'wide pri',
        ));

  }

}
