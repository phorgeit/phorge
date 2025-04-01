<?php

interface PhabricatorDashboardPanelContainerInterface {

  /**
   * Return a list of Dashboard Panel PHIDs used by this container.
   *
   * @return list<string> Dashboard panel PHIDs used by this container.
   */
  public function getDashboardPanelContainerPanelPHIDs();

}
