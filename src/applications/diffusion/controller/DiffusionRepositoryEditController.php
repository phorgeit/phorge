<?php

final class DiffusionRepositoryEditController
  extends DiffusionRepositoryManageController {

  public function handleRequest(AphrontRequest $request) {
    $engine = id(new DiffusionRepositoryEditEngine())
      ->setController($this);

    $id = $request->getURIData('id');
    if (!$id) {
      $this->requireApplicationCapability(
        DiffusionCreateRepositoriesCapability::CAPABILITY);

      $vcs = $request->getStr('vcs');
      $vcs_types = PhabricatorRepositoryType::getRepositoryTypeMap();
      if (!$vcs || empty($vcs_types[$vcs])) {
        return $this->buildVCSTypeResponse();
      }

      $engine
        ->addContextParameter('vcs', $vcs)
        ->setVersionControlSystem($vcs);
    }

    return $engine->buildResponse();
  }

  private function buildVCSTypeResponse() {
    $vcs_types = PhabricatorRepositoryType::getRepositoryTypeMap();

    $request = $this->getRequest();
    $viewer = $this->getViewer();

    $crumbs = $this->buildApplicationCrumbs();
    $crumbs->addTextCrumb(pht('Create Repository'));
    $crumbs->setBorder(true);

    $menu = id(new PHUIObjectItemListView())
      ->setViewer($viewer)
      ->setBig(true);

    $create_uri = $request->getRequestURI();

    foreach ($vcs_types as $vcs_key => $vcs_type) {
      $image = idx($vcs_type, 'icon');

      $menu->addItem(
        id(new PHUIObjectItemView())
          ->setHeader(idx($vcs_type, 'create.header'))
          ->setHref($create_uri->alter('vcs', $vcs_key))
          ->setImageIcon($image)
          ->setClickable(true)
          ->addAttribute(idx($vcs_type, 'create.subheader')));
    }

    $observe_href = PhabricatorEnv::getDoclink(
      'Diffusion User Guide: Existing Repositories');

    $menu->addItem(
      id(new PHUIObjectItemView())
        ->setHeader(pht('Import or Observe an Existing Repository'))
        ->setHref($observe_href)
        ->setImageIcon('fa-book')
        ->setClickable(true)
        ->addAttribute(
          pht(
            'Review the documentation describing how to import or observe an '.
            'existing repository.')));

    $box = id(new PHUIObjectBoxView())
      ->setHeaderText(pht('Create Repository'))
      ->setBackground(PHUIObjectBoxView::WHITE_CONFIG)
      ->setObjectList($menu);

    $launcher_view = id(new PHUILauncherView())
      ->appendChild($box);

    $view = id(new PHUITwoColumnView())
      ->setFooter($launcher_view);

    return $this->newPage()
      ->setTitle(pht('Choose Repository Type'))
      ->setCrumbs($crumbs)
      ->appendChild($view);
  }

}
