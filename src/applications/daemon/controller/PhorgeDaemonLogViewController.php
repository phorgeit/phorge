<?php

final class PhorgeDaemonLogViewController
  extends PhorgeDaemonController {

  public function handleRequest(AphrontRequest $request) {
    $viewer = $request->getViewer();
    $id = $request->getURIData('id');

    $log = id(new PhorgeDaemonLogQuery())
      ->setViewer($viewer)
      ->withIDs(array($id))
      ->setAllowStatusWrites(true)
      ->executeOne();
    if (!$log) {
      return new Aphront404Response();
    }

    $crumbs = $this->buildApplicationCrumbs();
    $crumbs->addTextCrumb(pht('Daemon %s', $log->getID()));
    $crumbs->setBorder(true);

    $header = id(new PHUIHeaderView())
      ->setHeader($log->getDaemon())
      ->setHeaderIcon('fa-pied-piper-alt');

    $tag = id(new PHUITagView())
      ->setType(PHUITagView::TYPE_STATE);

    $status = $log->getStatus();
    switch ($status) {
      case PhorgeDaemonLog::STATUS_UNKNOWN:
        $color = 'orange';
        $name = pht('Unknown');
        $icon = 'fa-warning';
        break;
      case PhorgeDaemonLog::STATUS_RUNNING:
        $color = 'green';
        $name = pht('Running');
        $icon = 'fa-rocket';
        break;
      case PhorgeDaemonLog::STATUS_DEAD:
        $color = 'red';
        $name = pht('Dead');
        $icon = 'fa-times';
        break;
      case PhorgeDaemonLog::STATUS_WAIT:
        $color = 'blue';
        $name = pht('Waiting');
        $icon = 'fa-clock-o';
        break;
      case PhorgeDaemonLog::STATUS_EXITING:
        $color = 'yellow';
        $name = pht('Exiting');
        $icon = 'fa-check';
        break;
      case PhorgeDaemonLog::STATUS_EXITED:
        $color = 'bluegrey';
        $name = pht('Exited');
        $icon = 'fa-check';
        break;
    }

    $header->setStatus($icon, $color, $name);

    $properties = $this->buildPropertyListView($log);

    $object_box = id(new PHUIObjectBoxView())
      ->setHeaderText(pht('Daemon Details'))
      ->setBackground(PHUIObjectBoxView::BLUE_PROPERTY)
      ->addPropertyList($properties);

    $view = id(new PHUITwoColumnView())
      ->setHeader($header)
      ->setFooter(array(
        $object_box,
      ));

    return $this->newPage()
      ->setTitle(pht('Daemon Log'))
      ->setCrumbs($crumbs)
      ->appendChild($view);

  }

  private function buildPropertyListView(PhorgeDaemonLog $daemon) {
    $request = $this->getRequest();
    $viewer = $request->getUser();

    $view = id(new PHUIPropertyListView())
      ->setUser($viewer);

    $id = $daemon->getID();
    $c_epoch = $daemon->getDateCreated();
    $u_epoch = $daemon->getDateModified();

    $unknown_time = PhorgeDaemonLogQuery::getTimeUntilUnknown();
    $dead_time = PhorgeDaemonLogQuery::getTimeUntilDead();
    $wait_time = PhutilDaemonHandle::getWaitBeforeRestart();

    $details = null;
    $status = $daemon->getStatus();
    switch ($status) {
      case PhorgeDaemonLog::STATUS_RUNNING:
        $details = pht(
          'This daemon is running normally and reported a status update '.
          'recently (within %s).',
          phutil_format_relative_time($unknown_time));
        break;
      case PhorgeDaemonLog::STATUS_UNKNOWN:
        $details = pht(
          'This daemon has not reported a status update recently (within %s). '.
          'It may have exited abruptly. After %s, it will be presumed dead.',
          phutil_format_relative_time($unknown_time),
          phutil_format_relative_time($dead_time));
        break;
      case PhorgeDaemonLog::STATUS_DEAD:
        $details = pht(
          'This daemon did not report a status update for %s. It is '.
          'presumed dead. Usually, this indicates that the daemon was '.
          'killed or otherwise exited abruptly with an error. You may '.
          'need to restart it.',
          phutil_format_relative_time($dead_time));
        break;
      case PhorgeDaemonLog::STATUS_WAIT:
        $details = pht(
          'This daemon is running normally and reported a status update '.
          'recently (within %s). The process is currently waiting to '.
          'restart, either because it is hibernating or because it '.
          'encountered an error.',
          phutil_format_relative_time($unknown_time));
        break;
      case PhorgeDaemonLog::STATUS_EXITING:
        $details = pht('This daemon is shutting down gracefully.');
        break;
      case PhorgeDaemonLog::STATUS_EXITED:
        $details = pht('This daemon exited normally and is no longer running.');
        break;
    }

    $view->addProperty(pht('Status Details'), $details);

    $view->addProperty(pht('Daemon Class'), $daemon->getDaemon());
    $view->addProperty(pht('Host'), $daemon->getHost());
    $view->addProperty(pht('PID'), $daemon->getPID());
    $view->addProperty(pht('Running as'), $daemon->getRunningAsUser());
    $view->addProperty(pht('Started'), phorge_datetime($c_epoch, $viewer));
    $view->addProperty(
      pht('Seen'),
      pht(
        '%s ago (%s)',
        phutil_format_relative_time(time() - $u_epoch),
        phorge_datetime($u_epoch, $viewer)));

    $argv = $daemon->getArgv();
    if (is_array($argv)) {
      $argv = implode("\n", $argv);
    }

    $view->addProperty(
      pht('Argv'),
      phutil_tag(
        'textarea',
        array(
          'style'   => 'width: 100%; height: 12em;',
        ),
        $argv));

    $view->addProperty(
      pht('View Full Logs'),
      phutil_tag(
        'tt',
        array(),
        "phorge/ $ ./bin/phd log --id {$id}"));


    return $view;
  }

}
