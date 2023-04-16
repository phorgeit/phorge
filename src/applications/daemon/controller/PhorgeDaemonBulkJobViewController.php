<?php

final class PhorgeDaemonBulkJobViewController
  extends PhorgeDaemonBulkJobController {

  public function handleRequest(AphrontRequest $request) {
    $viewer = $this->getViewer();

    $job = id(new PhorgeWorkerBulkJobQuery())
      ->setViewer($viewer)
      ->withIDs(array($request->getURIData('id')))
      ->executeOne();
    if (!$job) {
      return new Aphront404Response();
    }

    $title = pht('Bulk Job %d', $job->getID());

    $crumbs = $this->buildApplicationCrumbs();
    $crumbs->addTextCrumb($title);
    $crumbs->setBorder(true);

    $properties = $this->renderProperties($job);
    $curtain = $this->buildCurtainView($job);

    $box = id(new PHUIObjectBoxView())
      ->setHeaderText(pht('Details'))
      ->setBackground(PHUIObjectBoxView::BLUE_PROPERTY)
      ->addPropertyList($properties);

    $timeline = $this->buildTransactionTimeline(
      $job,
      new PhorgeWorkerBulkJobTransactionQuery());
    $timeline->setShouldTerminate(true);

    $header = id(new PHUIHeaderView())
      ->setHeader($title)
      ->setHeaderIcon('fa-hourglass');

    $view = id(new PHUITwoColumnView())
      ->setHeader($header)
      ->setCurtain($curtain)
      ->setMainColumn(array(
        $box,
        $timeline,
      ));

    return $this->newPage()
      ->setTitle($title)
      ->setCrumbs($crumbs)
      ->appendChild($view);
  }

  private function renderProperties(PhorgeWorkerBulkJob $job) {
    $viewer = $this->getViewer();

    $view = id(new PHUIPropertyListView())
      ->setUser($viewer)
      ->setObject($job);

    $view->addProperty(
      pht('Author'),
      $viewer->renderHandle($job->getAuthorPHID()));

    $view->addProperty(pht('Status'), $job->getStatusName());

    return $view;
  }

  private function buildCurtainView(PhorgeWorkerBulkJob $job) {
    $viewer = $this->getViewer();
    $curtain = $this->newCurtainView($job);

    foreach ($job->getCurtainActions($viewer) as $action) {
      $curtain->addAction($action);
    }

    return $curtain;
  }

}
