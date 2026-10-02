<?php

final class NuanceConsoleController extends NuanceController {

  public function shouldAllowPublic() {
    return true;
  }

  public function handleRequest(AphrontRequest $request) {
    $viewer = $request->getViewer();

    $menu = id(new PHUIObjectItemListView())
      ->setViewer($viewer)
      ->setBig(true);

    $menu->addItem(
      id(new PHUIObjectItemView())
        ->setHeader(pht('Queues'))
        ->setHref($this->getApplicationURI('queue/'))
        ->setImageIcon('fa-align-left')
        ->setClickable(true)
        ->addAttribute(pht('Manage Nuance queues.')));

    $menu->addItem(
      id(new PHUIObjectItemView())
        ->setHeader(pht('Sources'))
        ->setHref($this->getApplicationURI('source/'))
        ->setImageIcon('fa-filter')
        ->setClickable(true)
        ->addAttribute(pht('Manage Nuance sources.')));

    $menu->addItem(
      id(new PHUIObjectItemView())
        ->setHeader(pht('Items'))
        ->setHref($this->getApplicationURI('item/'))
        ->setImageIcon('fa-clone')
        ->setClickable(true)
        ->addAttribute(pht('Manage Nuance items.')));

    $crumbs = $this->buildApplicationCrumbs();
    $crumbs->addTextCrumb(pht('Console'));
    $crumbs->setBorder(true);

    $box = id(new PHUIObjectBoxView())
      ->setHeaderText(pht('Nuance Console'))
      ->setBackground(PHUIObjectBoxView::WHITE_CONFIG)
      ->setObjectList($menu);

    $launcher_view = id(new PHUILauncherView())
      ->appendChild($box);

    $view = id(new PHUITwoColumnView())
      ->setFooter($launcher_view);

    return $this->newPage()
      ->setTitle(pht('Nuance Console'))
      ->setCrumbs($crumbs)
      ->appendChild($view);
  }

}
