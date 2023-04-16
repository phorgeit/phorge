<?php

final class PhorgeDashboardPanelContainerIndexEngineExtension
  extends PhorgeEdgeIndexEngineExtension {

  const EXTENSIONKEY = 'dashboard.panel.container';

  public function getExtensionName() {
    return pht('Dashboard Panel Containers');
  }

  public function shouldIndexObject($object) {
    if (!($object instanceof PhorgeDashboardPanelContainerInterface)) {
      return false;
    }

    return true;
  }

  protected function getIndexEdgeType() {
    return PhorgeObjectUsesDashboardPanelEdgeType::EDGECONST;
  }

  protected function getIndexDestinationPHIDs($object) {
    return $object->getDashboardPanelContainerPanelPHIDs();
  }

}
