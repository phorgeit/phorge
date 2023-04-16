<?php

final class PhorgeDashboardPortalMenuItem
  extends PhorgeProfileMenuItem {

  const MENUITEMKEY = 'portal';

  public function getMenuItemTypeIcon() {
    return 'fa-pencil';
  }

  public function getDefaultName() {
    return pht('Manage Portal');
  }

  public function getMenuItemTypeName() {
    return pht('Manage Portal');
  }

  public function canHideMenuItem(
    PhorgeProfileMenuItemConfiguration $config) {
    return false;
  }

  public function canMakeDefault(
    PhorgeProfileMenuItemConfiguration $config) {
    return false;
  }

  public function getDisplayName(
    PhorgeProfileMenuItemConfiguration $config) {
    $name = $config->getMenuItemProperty('name');

    if (strlen($name)) {
      return $name;
    }

    return $this->getDefaultName();
  }

  public function buildEditEngineFields(
    PhorgeProfileMenuItemConfiguration $config) {
    return array(
      id(new PhorgeTextEditField())
        ->setKey('name')
        ->setLabel(pht('Name'))
        ->setPlaceholder($this->getDefaultName())
        ->setValue($config->getMenuItemProperty('name')),
    );
  }

  protected function newMenuItemViewList(
    PhorgeProfileMenuItemConfiguration $config) {
    $viewer = $this->getViewer();

    if (!$viewer->isLoggedIn()) {
      return array();
    }

    $uri = $this->getItemViewURI($config);
    $name = $this->getDisplayName($config);
    $icon = 'fa-pencil';

    $item = $this->newItemView()
      ->setURI($uri)
      ->setName($name)
      ->setIcon($icon);

    return array(
      $item,
    );
  }

  public function newPageContent(
    PhorgeProfileMenuItemConfiguration $config) {
    $viewer = $this->getViewer();
    $engine = $this->getEngine();
    $portal = $engine->getProfileObject();
    $controller = $engine->getController();

    $header = id(new PHUIHeaderView())
      ->setHeader(pht('Manage Portal'));

    $edit_uri = urisprintf(
      '/portal/edit/%d/',
      $portal->getID());

    $can_edit = PhorgePolicyFilter::hasCapability(
      $viewer,
      $portal,
      PhorgePolicyCapability::CAN_EDIT);

    $curtain = $controller->newCurtainView($portal)
      ->addAction(
        id(new PhorgeActionView())
          ->setName(pht('Edit Portal'))
          ->setIcon('fa-pencil')
          ->setDisabled(!$can_edit)
          ->setWorkflow(!$can_edit)
          ->setHref($edit_uri));

    $timeline = $controller->newTimelineView()
      ->setShouldTerminate(true);

    $view = id(new PHUITwoColumnView())
      ->setHeader($header)
      ->setCurtain($curtain)
      ->setMainColumn(
        array(
          $timeline,
        ));

    return $view;
  }


}
