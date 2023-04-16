<?php

final class PhorgeDaemonLogListController
  extends PhorgeDaemonController {

  public function handleRequest(AphrontRequest $request) {
    $viewer = $this->getViewer();

    $pager = new AphrontCursorPagerView();
    $pager->readFromRequest($request);

    $logs = id(new PhorgeDaemonLogQuery())
      ->setViewer($viewer)
      ->setAllowStatusWrites(true)
      ->executeWithCursorPager($pager);

    $daemon_table = id(new PhorgeDaemonLogListView())
      ->setViewer($viewer)
      ->setDaemonLogs($logs);

    $box = id(new PHUIObjectBoxView())
      ->setHeaderText(pht('All Daemons'))
      ->setTable($daemon_table);

    $crumbs = $this->buildApplicationCrumbs();
    $crumbs->addTextCrumb(pht('All Daemons'));

    $nav = $this->buildSideNavView();
    $nav->selectFilter('log');
    $nav->setCrumbs($crumbs);
    $nav->appendChild($box);
    $nav->appendChild($pager);

    return $this->newPage()
      ->setTitle(pht('All Daemons'))
      ->appendChild($nav);

  }

}
