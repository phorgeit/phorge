<?php

final class PhorgeEditorExtensionModule
  extends PhorgeConfigModule {

  public function getModuleKey() {
    return 'editor';
  }

  public function getModuleName() {
    return pht('Engine: Editor');
  }

  public function renderModuleStatus(AphrontRequest $request) {
    $viewer = $request->getViewer();

    $extensions = PhorgeEditorExtension::getAllExtensions();

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
