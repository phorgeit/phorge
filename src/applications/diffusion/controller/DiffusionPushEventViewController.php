<?php

final class DiffusionPushEventViewController
  extends DiffusionLogController {

  public function handleRequest(AphrontRequest $request) {
    $viewer = $this->getViewer();

    $event = id(new PhabricatorRepositoryPushEventQuery())
      ->setViewer($viewer)
      ->withIDs(array($request->getURIData('id')))
      ->needLogs(true)
      ->executeOne();
    if (!$event) {
      return new Aphront404Response();
    }

    $repository = $event->getRepository();
    $title = pht('Push %d', $event->getID());

    $crumbs = $this->buildApplicationCrumbs();
    $crumbs->addTextCrumb(
      $repository->getName(),
      $repository->getURI());

    $crumbs->addTextCrumb(
      pht('Push Logs'),
      $this->getApplicationURI(
        'pushlog/?repositories='.$repository->getMonogram()));
    $crumbs->addTextCrumb($title);

    $event_properties = $this->buildPropertyList($event);

    $detail_box = id(new PHUIObjectBoxView())
      ->setHeaderText($title)
      ->addPropertyList($event_properties);

    $commits = $this->loadCommits($event);
    $commits_table = $this->renderCommitsTable($event, $commits);

    $commits_box = id(new PHUIObjectBoxView())
      ->setHeaderText(pht('Pushed Commits'))
      ->setTable($commits_table);

    $logs = $event->getLogs();

    $updates_table = id(new DiffusionPushLogListView())
      ->setUser($viewer)
      ->setLogs($logs);

    $update_box = id(new PHUIObjectBoxView())
      ->setHeaderText(pht('All Pushed Updates'))
      ->setTable($updates_table);

    return $this->newPage()
      ->setTitle($title)
      ->setCrumbs($crumbs)
      ->appendChild(
        array(
          $detail_box,
          $commits_box,
          $update_box,
        ));
  }

  private function buildPropertyList(PhabricatorRepositoryPushEvent $event) {
    $viewer = $this->getRequest()->getUser();
    $view = new PHUIPropertyListView();

    $view->addProperty(
      pht('Pushed At'),
      vixon_datetime($event->getEpoch(), $viewer));

    $view->addProperty(
      pht('Pushed By'),
      $viewer->renderHandle($event->getPusherPHID()));

    $view->addProperty(
      pht('Pushed Via'),
      $event->getRemoteProtocol());

    return $view;
  }

  private function loadCommits(PhabricatorRepositoryPushEvent $event) {
    $viewer = $this->getRequest()->getUser();

    $identifiers = array();
    foreach ($event->getLogs() as $log) {
      if ($log->getRefType() == PhabricatorRepositoryPushLog::REFTYPE_COMMIT) {
        $identifiers[] = $log->getRefNew();
      }
    }

    if (!$identifiers) {
      return array();
    }

    // NOTE: Commits may not have been parsed/discovered yet. We need to return
    // the identifiers no matter what. If possible, we'll also return the
    // corresponding commits.

    $commits = id(new DiffusionCommitQuery())
      ->setViewer($viewer)
      ->withRepository($event->getRepository())
      ->withIdentifiers($identifiers)
      ->execute();

    $commits = mpull($commits, null, 'getCommitIdentifier');

    $results = array();
    foreach ($identifiers as $identifier) {
      $results[$identifier] = idx($commits, $identifier);
    }

    return $results;
  }

  private function renderCommitsTable(
    PhabricatorRepositoryPushEvent $event,
    array $commits) {

    $viewer = $this->getRequest()->getUser();
    $repository = $event->getRepository();

    $rows = array();
    foreach ($commits as $identifier => $commit) {
      if ($commit) {
        $partial_import = PhabricatorRepositoryCommit::IMPORTED_MESSAGE |
                          PhabricatorRepositoryCommit::IMPORTED_CHANGE;
        if ($commit->isPartiallyImported($partial_import)) {
          $summary = AphrontTableView::renderSingleDisplayLine(
            $commit->getSummary());
        } else {
          $summary = phutil_tag('em', array(), pht('Importing...'));
        }
      } else {
        $summary = phutil_tag('em', array(), pht('Discovering...'));
      }

      $commit_name = $repository->formatCommitName($identifier);
      if ($commit) {
        $commit_name = phutil_tag(
          'a',
          array(
            'href' => '/'.$commit_name,
          ),
          $commit_name);
      }

      $rows[] = array(
        $commit_name,
        $summary,
      );
    }

    $table = id(new AphrontTableView($rows))
      ->setNoDataString(pht("This push didn't push any new commits."))
      ->setHeaders(
        array(
          pht('Commit'),
          pht('Summary'),
        ))
      ->setColumnClasses(
        array(
          'n',
          'wide',
        ));

    return $table;
  }

}
