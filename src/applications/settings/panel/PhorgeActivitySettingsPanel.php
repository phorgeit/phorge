<?php

final class PhorgeActivitySettingsPanel extends PhorgeSettingsPanel {

  public function getPanelKey() {
    return 'activity';
  }

  public function getPanelName() {
    return pht('Activity Logs');
  }

  public function getPanelMenuIcon() {
    return 'fa-list';
  }

  public function getPanelGroupKey() {
    return PhorgeSettingsLogsPanelGroup::PANELGROUPKEY;
  }

  public function processRequest(AphrontRequest $request) {
    $viewer = $request->getUser();
    $user = $this->getUser();

    $pager = id(new AphrontCursorPagerView())
      ->readFromRequest($request);

    $logs = id(new PhorgePeopleLogQuery())
      ->setViewer($viewer)
      ->withRelatedPHIDs(array($user->getPHID()))
      ->executeWithCursorPager($pager);

    $table = id(new PhorgeUserLogView())
      ->setUser($viewer)
      ->setLogs($logs);

    $panel = $this->newBox(pht('Account Activity Logs'), $table);

    $pager_box = id(new PHUIBoxView())
      ->addMargin(PHUI::MARGIN_LARGE)
      ->appendChild($pager);

    return array($panel, $pager_box);
  }

  public function isManagementPanel() {
    return true;
  }

}
