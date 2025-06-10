<?php

final class PhabricatorDaemonConsoleController
  extends PhabricatorDaemonController {

  public function handleRequest(AphrontRequest $request) {
    $viewer = $this->getViewer();

    $window_start = (time() - (60 * 15));

    // Assume daemons spend about 250ms second in overhead per task acquiring
    // leases and doing other bookkeeping. This is probably an over-estimation,
    // but we'd rather show that utilization is too high than too low.
    $lease_overhead = 0.250;

    $completed = id(new PhabricatorWorkerArchiveTaskQuery())
      ->withDateModifiedSince($window_start)
      ->execute();

    $failed = id(new PhabricatorWorkerActiveTask())->loadAllWhere(
      'failureTime > %d',
      $window_start);

    $usage_total = 0;
    $usage_start = PHP_INT_MAX;

    $completed_info = array();
    foreach ($completed as $completed_task) {
      $class = $completed_task->getTaskClass();
      if (empty($completed_info[$class])) {
        $completed_info[$class] = array(
          'n' => 0,
          'duration' => 0,
          'queueTime' => 0,
        );
      }
      $completed_info[$class]['n']++;
      $duration = $completed_task->getDuration();
      $completed_info[$class]['duration'] += $duration;

      // NOTE: Duration is in microseconds, but we're just using seconds to
      // compute utilization.
      $usage_total += $lease_overhead + ($duration / 1000000);
      $usage_start = min($usage_start, $completed_task->getDateModified());

      $date_archived = $completed_task->getArchivedEpoch();
      $queue_seconds = $date_archived - $completed_task->getDateCreated();

      // Don't measure queue time for tasks that completed in the same
      // epoch-second they were created in.
      if ($queue_seconds > 0) {
        $sec_in_us = phutil_units('1 second in microseconds');
        $queue_us = $queue_seconds * $sec_in_us;
        $queue_exclusive_us = $queue_us - $duration;
        $queue_exclusive_seconds = $queue_exclusive_us / $sec_in_us;
        $rounded = floor($queue_exclusive_seconds);
        $completed_info[$class]['queueTime'] += $rounded;
      }
    }

    $completed_info = isort($completed_info, 'n');

    $rows = array();
    foreach ($completed_info as $class => $info) {
      $duration_avg = new PhutilNumber((int)($info['duration'] / $info['n']));
      $queue_avg = new PhutilNumber((int)($info['queueTime'] / $info['n']));
      $rows[] = array(
        $class,
        number_format($info['n']),
        pht('%s us', $duration_avg),
        pht('%s s', $queue_avg),
      );
    }

    if ($failed) {
      // Add the time it takes to restart the daemons. This includes a guess
      // about other overhead of 2X.
      $restart_delay = PhutilDaemonHandle::getWaitBeforeRestart();
      $usage_total += $restart_delay * count($failed) * 2;
      foreach ($failed as $failed_task) {
        $usage_start = min($usage_start, $failed_task->getFailureTime());
      }

      $rows[] = array(
        phutil_tag('em', array(), pht('Temporary Failures')),
        count($failed),
        null,
        null,
      );
    }

    $logs = id(new PhabricatorDaemonLogQuery())
      ->setViewer($viewer)
      ->withStatus(PhabricatorDaemonLogQuery::STATUS_ALIVE)
      ->setAllowStatusWrites(true)
      ->execute();

    $taskmasters = 0;
    foreach ($logs as $log) {
      if ($log->getDaemon() == 'PhabricatorTaskmasterDaemon') {
        $taskmasters++;
      }
    }

    if ($taskmasters && $usage_total) {
      // Total number of wall-time seconds the daemons have been running since
      // the oldest event. For very short times round up to 15s so we don't
      // render any ridiculous numbers if you reload the page immediately after
      // restarting the daemons.
      $available_time = $taskmasters * max(15, (time() - $usage_start));

      // Percentage of those wall-time seconds we can account for, which the
      // daemons spent doing work:
      $used_time = ($usage_total / $available_time);

      $rows[] = array(
        phutil_tag('em', array(), pht('Queue Utilization (Approximate)')),
        sprintf('%.1f%%', 100 * $used_time),
        null,
        null,
      );
    }

    $completed_table = new AphrontTableView($rows);
    $completed_table->setNoDataString(
      pht('No tasks have completed in the last 15 minutes.'));
    $completed_table->setHeaders(
      array(
        pht('Class'),
        pht('Count'),
        pht('Average Duration'),
        pht('Average Queue Time'),
      ));
    $completed_table->setColumnClasses(
      array(
        'wide',
        'n',
        'n',
        'n',
      ));

    $completed_panel = id(new PHUIObjectBoxView())
      ->setHeaderText(pht('Recently Completed Tasks (Last 15m)'))
      ->setTable($completed_table);

    $daemon_table = id(new PhabricatorDaemonLogListView())
      ->setUser($viewer)
      ->setDaemonLogs($logs);

    $daemon_panel = id(new PHUIObjectBoxView())
      ->setHeaderText(pht('Active Daemons'))
      ->setTable($daemon_table);

    $tasks = id(new PhabricatorWorkerLeaseQuery())
      ->setSkipLease(true)
      ->withLeasedTasks(true)
      ->setLimit(100)
      ->execute();

    $tasks_table = id(new PhabricatorDaemonTasksTableView())
      ->setTasks($tasks)
      ->setNoDataString(pht('No tasks are leased by workers.'));

    $leased_panel = id(new PHUIObjectBoxView())
      ->setHeaderText(pht('Leased Tasks'))
      ->setTable($tasks_table);

    $task_table = new PhabricatorWorkerActiveTask();
    $queued = queryfx_all(
      $task_table->establishConnection('r'),
      'SELECT taskClass, count(*) N FROM %T GROUP BY taskClass
        ORDER BY N DESC',
      $task_table->getTableName());

    $rows = array();
    foreach ($queued as $row) {
      $rows[] = array(
        $row['taskClass'],
        number_format($row['N']),
      );
    }

    $queued_table = new AphrontTableView($rows);
    $queued_table->setHeaders(
      array(
        pht('Class'),
        pht('Count'),
      ));
    $queued_table->setColumnClasses(
      array(
        'wide',
        'n',
      ));
    $queued_table->setNoDataString(pht('Task queue is empty.'));

    $queued_panel = new PHUIObjectBoxView();
    $queued_panel->setHeaderText(pht('Queued Tasks'));
    $queued_panel->setTable($queued_table);

    $upcoming = id(new PhabricatorWorkerLeaseQuery())
      ->setLimit(10)
      ->setSkipLease(true)
      ->execute();

    $upcoming_panel = id(new PHUIObjectBoxView())
      ->setHeaderText(pht('Next In Queue'))
      ->setTable(
        id(new PhabricatorDaemonTasksTableView())
          ->setTasks($upcoming)
          ->setNoDataString(pht('Task queue is empty.')));

    $triggers = id(new PhabricatorWorkerTriggerQuery())
      ->setViewer($viewer)
      ->setOrder(PhabricatorWorkerTriggerQuery::ORDER_EXECUTION)
      ->withNextEventBetween(0, null)
      ->needEvents(true)
      ->setLimit(10)
      ->execute();

    $triggers_table = $this->buildTriggersTable($triggers);

    $triggers_panel = id(new PHUIObjectBoxView())
      ->setHeaderText(pht('Upcoming Triggers'))
      ->setTable($triggers_table);

    $crumbs = $this->buildApplicationCrumbs();
    $crumbs->addTextCrumb(pht('Console'));

    $nav = $this->buildSideNavView();
    $nav->selectFilter('/');
    $nav->appendChild(
      array(
        $crumbs,
        $completed_panel,
        $daemon_panel,
        $queued_panel,
        $leased_panel,
        $upcoming_panel,
        $triggers_panel,
      ));

    return $this->newPage()
      ->setTitle(pht('Console'))
      ->appendChild($nav);

  }

  private function buildTriggersTable(array $triggers) {
    $viewer = $this->getViewer();

    $rows = array();
    foreach ($triggers as $trigger) {
      $event = $trigger->getEvent();
      if ($event) {
        $last_epoch = $event->getLastEventEpoch();
        $next_epoch = $event->getNextEventEpoch();
      } else {
        $last_epoch = null;
        $next_epoch = null;
      }

      $rows[] = array(
        $trigger->getID(),
        $trigger->getClockClass(),
        $trigger->getActionClass(),
        $last_epoch ? vixon_datetime($last_epoch, $viewer) : null,
        $next_epoch ? vixon_datetime($next_epoch, $viewer) : null,
      );
    }

    return id(new AphrontTableView($rows))
      ->setNoDataString(pht('There are no upcoming event triggers.'))
      ->setHeaders(
        array(
          pht('ID'),
          pht('Clock'),
          pht('Action'),
          pht('Last'),
          pht('Next'),
        ))
      ->setColumnClasses(
        array(
          '',
          '',
          'wide',
          'date',
          'date',
        ));
  }

}
