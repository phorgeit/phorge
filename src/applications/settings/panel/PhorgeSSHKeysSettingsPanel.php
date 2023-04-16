<?php

final class PhorgeSSHKeysSettingsPanel extends PhorgeSettingsPanel {

  public function isManagementPanel() {
    if ($this->getUser()->getIsMailingList()) {
      return false;
    }

    return true;
  }

  public function getPanelKey() {
    return 'ssh';
  }

  public function getPanelName() {
    return pht('SSH Public Keys');
  }

  public function getPanelMenuIcon() {
    return 'fa-file-text-o';
  }

  public function getPanelGroupKey() {
    return PhorgeSettingsAuthenticationPanelGroup::PANELGROUPKEY;
  }

  public function processRequest(AphrontRequest $request) {
    $user = $this->getUser();
    $viewer = $request->getUser();

    $keys = id(new PhorgeAuthSSHKeyQuery())
      ->setViewer($viewer)
      ->withObjectPHIDs(array($user->getPHID()))
      ->withIsActive(true)
      ->execute();

    $table = id(new PhorgeAuthSSHKeyTableView())
      ->setUser($viewer)
      ->setKeys($keys)
      ->setCanEdit(true)
      ->setNoDataString(pht("You haven't added any SSH Public Keys."));

    $panel = new PHUIObjectBoxView();
    $header = new PHUIHeaderView();

    $ssh_actions = PhorgeAuthSSHKeyTableView::newKeyActionsMenu(
      $viewer,
      $user);

    return $this->newBox(pht('SSH Public Keys'), $table, array($ssh_actions));
  }

}
