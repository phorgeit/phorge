<?php

final class PhorgeConfigEdgeModule extends PhorgeConfigModule {

  public function getModuleKey() {
    return 'edge';
  }

  public function getModuleName() {
    return pht('Edge Types');
  }

  public function renderModuleStatus(AphrontRequest $request) {
    $viewer = $request->getViewer();

    $types = PhorgeEdgeType::getAllTypes();
    $types = msort($types, 'getEdgeConstant');

    $rows = array();
    foreach ($types as $key => $type) {
      $rows[] = array(
        $type->getEdgeConstant(),
        $type->getInverseEdgeConstant(),
        get_class($type),
      );
    }

    return id(new AphrontTableView($rows))
      ->setHeaders(
        array(
          pht('Constant'),
          pht('Inverse'),
          pht('Class'),
        ))
      ->setColumnClasses(
        array(
          null,
          null,
          'pri wide',
        ));
  }

}
