<?php

final class PhabricatorPackagesConsoleController
  extends PhabricatorPackagesController {

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
        ->setHeader(pht('Publishers'))
        ->setHref($this->getApplicationURI('publisher/'))
        ->setImageIcon('fa-university')
        ->setClickable(true)
        ->addAttribute(
          pht(
            'Manage software publishers.')));

    $menu->addItem(
      id(new PHUIObjectItemView())
        ->setHeader(pht('Packages'))
        ->setHref($this->getApplicationURI('package/'))
        ->setImageIcon('fa-gift')
        ->setClickable(true)
        ->addAttribute(
          pht(
            'Create and update software packages.')));

    $menu->addItem(
      id(new PHUIObjectItemView())
        ->setHeader(pht('Versions'))
        ->setHref($this->getApplicationURI('version/'))
        ->setImageIcon('fa-birthday-cake')
        ->setClickable(true)
        ->addAttribute(
          pht(
            'Release and update package versions.')));

    $crumbs = $this->buildApplicationCrumbs();
    $crumbs->addTextCrumb(pht('Console'));
    $crumbs->setBorder(true);

    $box = id(new PHUIObjectBoxView())
      ->setHeaderText(pht('Packages Console'))
      ->setBackground(PHUIObjectBoxView::WHITE_CONFIG)
      ->setObjectList($menu);

    $launcher_view = id(new PHUILauncherView())
      ->appendChild($box);

    $view = id(new PHUITwoColumnView())
      ->setFooter($launcher_view);

    return $this->newPage()
      ->setTitle(pht('Packages Console'))
      ->setCrumbs($crumbs)
      ->appendChild($view);
  }

}
