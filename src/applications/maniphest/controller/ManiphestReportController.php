<?php

final class ManiphestReportController extends ManiphestController {

  private $view;

  public function handleRequest(AphrontRequest $request) {
    $viewer = $this->getViewer();
    $this->view = $request->getURIData('view');

    if ($request->isFormPost()) {
      $uri = $request->getRequestURI();

      $project = head($request->getArr('set_project'));
      $project = nonempty($project, null);

      if ($project !== null) {
        $uri->replaceQueryParam('project', $project);
      } else {
        $uri->removeQueryParam('project');
      }

      $window = $request->getStr('set_window');
      if ($window !== null) {
        $uri->replaceQueryParam('window', $window);
      } else {
        $uri->removeQueryParam('window');
      }

      return id(new AphrontRedirectResponse())->setURI($uri);
    }

    $nav = new AphrontSideNavFilterView();
    $nav->setBaseURI(new PhutilURI('/maniphest/report/'));
    $nav->addLabel(pht('Open Tasks'));
    $nav->addFilter('user', pht('By User'));
    $nav->addFilter('project', pht('By Project'));

    $class = 'PhabricatorFactApplication';
    if (PhabricatorApplication::isClassInstalledForViewer($class, $viewer)) {
      $nav->addLabel(pht('Burnup'));
      $nav->addFilter('burn', pht('Burnup Rate'));
    }

    $this->view = $nav->selectFilter($this->view, 'user');

    require_celerity_resource('maniphest-report-css');

    switch ($this->view) {
      case 'burn':
        $core = $this->renderBurn();
        break;
      case 'user':
      case 'project':
        $core = $this->renderOpenTasks();
        break;
      default:
        return new Aphront404Response();
    }

    $crumbs = $this->buildApplicationCrumbs()
      ->addTextCrumb(pht('Reports'));

    $nav->appendChild($core);
    $title = pht('Maniphest Reports');

    return $this->newPage()
      ->setTitle($title)
      ->setCrumbs($crumbs)
      ->setNavigation($nav);

  }

  /**
   * Render the "Burnup Rate" on /maniphest/report/burn/.
   *
   * Ironically this is not called for the "Burndown" on /project/reports/$id/
   * as that's handled by PhabricatorProjectReportsController instead.
   *
   * @return array<AphrontListFilterView, PHUIObjectBoxView>
   */
  public function renderBurn() {
    $request = $this->getRequest();
    $viewer = $request->getUser();

    $handle = null;

    $project_phid = $request->getStr('project');
    if ($project_phid) {
      $phids = array($project_phid);
      $handles = $this->loadViewerHandles($phids);
      $handle = $handles[$project_phid];
    }

    $tokens = array();
    if ($handle) {
      $tokens = array($handle);
    }

    $filter = $this->renderReportFilters($tokens, $has_window = false);

    if ($project_phid) {
      $projects = id(new PhabricatorProjectQuery())
        ->setViewer($viewer)
        ->withPHIDs(array($project_phid))
        ->execute();
    } else {
      $projects = array();
    }

    $panel = id(new PhabricatorProjectBurndownChartEngine())
      ->setViewer($viewer)
      ->setProjects($projects)
      ->buildChartPanel();

    $panel->setName(pht('Burnup Rate'));

    $chart_view = id(new PhabricatorDashboardPanelRenderingEngine())
      ->setViewer($viewer)
      ->setPanel($panel)
      ->setParentPanelPHIDs(array())
      ->renderPanel();

    return array($filter, $chart_view);
  }

  /**
   * @param array $tokens
   * @param bool $has_window
   * @return AphrontListFilterView
   */
  private function renderReportFilters(array $tokens, $has_window) {
    $request = $this->getRequest();
    $viewer = $request->getUser();

    $form = id(new AphrontFormView())
      ->setUser($viewer)
      ->appendControl(
        id(new AphrontFormTokenizerControl())
          ->setDatasource(new PhabricatorProjectDatasource())
          ->setLabel(pht('Project'))
          ->setLimit(1)
          ->setName('set_project')
          // TODO: This is silly, but this is Maniphest reports.
          ->setValue(mpull($tokens, 'getPHID')));

    if ($has_window) {
      list($window_str, $ignored, $window_error) = $this->getWindow();
      $form
        ->appendChild(
          id(new AphrontFormTextControl())
            ->setLabel(pht('Recently Means'))
            ->setName('set_window')
            ->setCaption(
              pht('Configure the cutoff for the "Recently Closed" column.'))
            ->setValue($window_str)
            ->setError($window_error));
    }

    $form
      ->appendChild(
        id(new AphrontFormSubmitControl())
          ->setValue(pht('Filter By Project')));

    $filter = new AphrontListFilterView();
    $filter->appendChild($form);

    return $filter;
  }

  /**
   * @return int 50, the default value of the default "normal" Priority
   */
  private function getAveragePriority() {
    // TODO: This is sort of a hard-code for the default "normal" status.
    // When reports are more powerful, this should be made more general.
    return 50;
  }

  /**
   * Render all table cells in the "Open Tasks" table on /maniphest/report/*.
   *
   * @return array<AphrontListFilterView,PHUIObjectBoxView>
   */
  public function renderOpenTasks() {
    $request = $this->getRequest();
    $viewer = $request->getUser();


    $query = id(new ManiphestTaskQuery())
      ->setViewer($viewer)
      ->withStatuses(ManiphestTaskStatus::getOpenStatusConstants());

    switch ($this->view) {
      case 'project':
        $query->needProjectPHIDs(true);
        break;
    }

    $project_phid = $request->getStr('project');
    $project_handle = null;
    if ($project_phid) {
      $phids = array($project_phid);
      $handles = $this->loadViewerHandles($phids);
      $project_handle = $handles[$project_phid];

      $query->withEdgeLogicPHIDs(
        PhabricatorProjectObjectHasProjectEdgeType::EDGECONST,
        PhabricatorQueryConstraint::OPERATOR_OR,
        $phids);
    }

    $tasks = $query->execute();

    $recently_closed = $this->loadRecentlyClosedTasks();

    $date = phabricator_date(time(), $viewer);

    switch ($this->view) {
      case 'user':
        $result = mgroup($tasks, 'getOwnerPHID');
        $leftover = idx($result, '', array());
        unset($result['']);

        $result_closed = mgroup($recently_closed, 'getOwnerPHID');
        $leftover_closed = idx($result_closed, '', array());
        unset($result_closed['']);

        $base_link = '/maniphest/?assigned=';
        $leftover_name = phutil_tag('em', array(), pht('(Up For Grabs)'));
        $col_header = pht('User');
        $header = pht('Open Tasks by User and Priority (%s)', $date);
        break;
      case 'project':
        $result = array();
        $leftover = array();
        foreach ($tasks as $task) {
          $phids = $task->getProjectPHIDs();
          if ($phids) {
            foreach ($phids as $project_phid) {
              $result[$project_phid][] = $task;
            }
          } else {
            $leftover[] = $task;
          }
        }

        $result_closed = array();
        $leftover_closed = array();
        foreach ($recently_closed as $task) {
          $phids = $task->getProjectPHIDs();
          if ($phids) {
            foreach ($phids as $project_phid) {
              $result_closed[$project_phid][] = $task;
            }
          } else {
            $leftover_closed[] = $task;
          }
        }

        $base_link = '/maniphest/?projects=';
        $leftover_name = phutil_tag('em', array(), pht('(No Project)'));
        $col_header = pht('Project');
        $header = pht('Open Tasks by Project and Priority (%s)', $date);
        break;
    }

    $phids = array_keys($result);
    $handles = $this->loadViewerHandles($phids);
    $handles = msort($handles, 'getName');

    $order = $request->getStr('order', 'name');
    list($order, $reverse) = AphrontTableView::parseSort($order);

    require_celerity_resource('aphront-tooltip-css');
    Javelin::initBehavior('phabricator-tooltips', array());

    $rows = array();
    $pri_total = array();
    foreach (array_merge($handles, array(null)) as $handle) {
      if ($handle) {
        if (($project_handle) &&
            ($project_handle->getPHID() == $handle->getPHID())) {
          // If filtering by, e.g., "bugs", don't show a "bugs" group.
          continue;
        }

        $tasks = idx($result, $handle->getPHID(), array());
        $name = phutil_tag(
          'a',
          array(
            'href' => $base_link.$handle->getPHID(),
          ),
          $handle->getName());
        $closed = idx($result_closed, $handle->getPHID(), array());
      } else {
        $tasks = $leftover;
        $name  = $leftover_name;
        $closed = $leftover_closed;
      }

      $taskv = $tasks;
      $tasks = mgroup($tasks, 'getPriority');

      $row = array();
      $row[] = $name;
      $total = 0;
      foreach (ManiphestTaskPriority::getTaskPriorityMap() as $pri => $label) {
        $n = count(idx($tasks, $pri, array()));
        if ($n == 0) {
          $row[] = '-';
        } else {
          $row[] = number_format($n);
        }
        $total += $n;
      }
      $row[] = number_format($total);

      list($link, $oldest_all) = $this->renderOldest($taskv);
      $row[] = $link;

      $normal_or_better = array();
      foreach ($taskv as $id => $task) {
        if ($task->getPriority() < $this->getAveragePriority()) {
          continue;
        }
        $normal_or_better[$id] = $task;
      }

      list($link, $oldest_pri) = $this->renderOldest($normal_or_better);
      $row[] = $link;

      if ($closed) {
        $task_ids = implode(',', mpull($closed, 'getID'));
        $row[] = phutil_tag(
          'a',
          array(
            'href' => '/maniphest/?ids='.$task_ids,
            'target' => '_blank',
          ),
          number_format(count($closed)));
      } else {
        $row[] = '-';
      }

      switch ($order) {
        case 'total':
          $row['sort'] = $total;
          break;
        case 'oldest-all':
          $row['sort'] = $oldest_all;
          break;
        case 'oldest-pri':
          $row['sort'] = $oldest_pri;
          break;
        case 'closed':
          $row['sort'] = count($closed);
          break;
        case 'name':
        default:
          $row['sort'] = $handle ? $handle->getName() : '~';
          break;
      }

      $rows[] = $row;
    }

    $rows = isort($rows, 'sort');
    foreach ($rows as $k => $row) {
      unset($rows[$k]['sort']);
    }
    if ($reverse) {
      $rows = array_reverse($rows);
    }

    $cname = array($col_header);
    $cclass = array('pri right wide');
    $pri_map = ManiphestTaskPriority::getShortNameMap();
    foreach ($pri_map as $pri => $label) {
      $cname[] = $label;
      $cclass[] = 'n';
    }
    $cname[] = pht('Total');
    $cclass[] = 'n';
    $cname[] = javelin_tag(
      'span',
      array(
        'sigil' => 'has-tooltip',
        'meta'  => array(
          'tip' => pht('Oldest open task.'),
          'size' => 200,
        ),
      ),
      pht('Oldest (All)'));
    $cclass[] = 'n';
    $low_priorities = array();
    $priorities_map = ManiphestTaskPriority::getTaskPriorityMap();
    $normal_priority = $this->getAveragePriority();
    foreach ($priorities_map as $pri => $full_label) {
      if ($pri < $normal_priority) {
        $low_priorities[] = $full_label;
      }
    }
    $pri_string = implode(', ', $low_priorities);
    $cname[] = javelin_tag(
      'span',
      array(
        'sigil' => 'has-tooltip',
        'meta'  => array(
          'tip' => pht(
            'Oldest open task, excluding those with priority %s', $pri_string),
          'size' => 200,
        ),
      ),
      pht('Oldest (Pri)'));
    $cclass[] = 'n';

    list($ignored, $window_epoch) = $this->getWindow();
    $edate = phabricator_datetime($window_epoch, $viewer);
    $cname[] = javelin_tag(
      'span',
      array(
        'sigil' => 'has-tooltip',
        'meta'  => array(
          'tip'  => pht('Closed after %s', $edate),
          'size' => 260,
        ),
      ),
      pht('Recently Closed'));
    $cclass[] = 'n';

    $table = new AphrontTableView($rows);
    $table->setHeaders($cname);
    $table->setColumnClasses($cclass);
    $table->makeSortable(
      $request->getRequestURI(),
      'order',
      $order,
      $reverse,
      array(
        'name',
        null,
        null,
        null,
        null,
        null,
        null,
        'total',
        'oldest-all',
        'oldest-pri',
        'closed',
      ));

    $panel = new PHUIObjectBoxView();
    $panel->setHeaderText($header);
    $panel->setTable($table);

    $tokens = array();
    if ($project_handle) {
      $tokens = array($project_handle);
    }
    $filter = $this->renderReportFilters($tokens, $has_window = true);

    return array($filter, $panel);
  }


  /**
   * Load all tasks that have been recently closed.
   * This is used for the "Recently Closed" column on /maniphest/report/*.
   *
   * @return array<ManiphestTask|null>
   */
  private function loadRecentlyClosedTasks() {
    list($ignored, $window_epoch) = $this->getWindow();

    $table = new ManiphestTask();
    $xtable = new ManiphestTransaction();
    $conn_r = $table->establishConnection('r');

    // TODO: Gross. This table is not meant to be queried like this. Build
    // real stats tables.

    $open_status_list = array();
    foreach (ManiphestTaskStatus::getOpenStatusConstants() as $constant) {
      $open_status_list[] = json_encode((string)$constant);
    }

    $rows = queryfx_all(
      $conn_r,
      'SELECT t.id FROM %T t JOIN %T x ON x.objectPHID = t.phid
        WHERE t.status NOT IN (%Ls)
        AND x.oldValue IN (null, %Ls)
        AND x.newValue NOT IN (%Ls)
        AND t.dateModified >= %d
        AND x.dateCreated >= %d',
      $table->getTableName(),
      $xtable->getTableName(),
      ManiphestTaskStatus::getOpenStatusConstants(),
      $open_status_list,
      $open_status_list,
      $window_epoch,
      $window_epoch);

    if (!$rows) {
      return array();
    }

    $ids = ipull($rows, 'id');

    $query = id(new ManiphestTaskQuery())
      ->setViewer($this->getRequest()->getUser())
      ->withIDs($ids);

    switch ($this->view) {
      case 'project':
        $query->needProjectPHIDs(true);
        break;
    }

    return $query->execute();
  }

  /**
   * Parse the "Recently Means" filter on /maniphest/report/* into:
   *    - A string representation, like "12 AM 7 days ago" (default);
   *    - a locale-aware epoch representation; and
   *    - a possible error.
   * This is used for the "Recently Closed" column on /maniphest/report/*.
   *
   * @return array Array with three items: "Recently Means" user input;
   *         Resulting epoch timeframe used to get "Recently Closed" numbers
   *         (when user input is invalid, it defaults to a week ago); "Invalid"
   *         if first parameter could not be parsed as an epoch, else null.
   *         array<string,integer,string|null>
   */
  private function getWindow() {
    $request = $this->getRequest();
    $viewer = $request->getUser();

    $window_str = $this->getRequest()->getStr('window', '12 AM 7 days ago');

    $error = null;
    $window_epoch = null;

    // Do locale-aware parsing so that the user's timezone is assumed for
    // time windows like "3 PM", rather than assuming the server timezone.

    $window_epoch = PhabricatorTime::parseLocalTime($window_str, $viewer);
    if (!$window_epoch) {
      $error = 'Invalid';
      $window_epoch = time() - (60 * 60 * 24 * 7);
    }

    // If the time ends up in the future, convert it to the corresponding time
    // and equal distance in the past. This is so users can type "6 days" (which
    // means "6 days from now") and get the behavior of "6 days ago", rather
    // than no results (because the window epoch is in the future). This might
    // be a little confusing because it causes "tomorrow" to mean "yesterday"
    // and "2022" (or whatever) to mean "ten years ago", but these inputs are
    // nonsense anyway.

    if ($window_epoch > time()) {
      $window_epoch = time() - ($window_epoch - time());
    }

    return array($window_str, $window_epoch, $error);
  }

  /**
   * Render date of oldest open task per user or per project with a link.
   * Used on /maniphest/report/user/ and /maniphest/report/project/ URIs.
   *
   * @return array<PhutilSafeHTML,int> HTML link markup and the timespan
   *         (as epoch) since task creation
   */
  private function renderOldest(array $tasks) {
    assert_instances_of($tasks, 'ManiphestTask');
    $oldest = null;
    foreach ($tasks as $id => $task) {
      if (($oldest === null) ||
          ($task->getDateCreated() < $tasks[$oldest]->getDateCreated())) {
        $oldest = $id;
      }
    }

    if ($oldest === null) {
      return array('-', 0);
    }

    $oldest = $tasks[$oldest];

    $raw_age = (time() - $oldest->getDateCreated());
    $age = number_format($raw_age / (24 * 60 * 60)).' d';

    $link = javelin_tag(
      'a',
      array(
        'href'  => '/T'.$oldest->getID(),
        'sigil' => 'has-tooltip',
        'meta'  => array(
          'tip' => 'T'.$oldest->getID().': '.$oldest->getTitle(),
        ),
        'target' => '_blank',
      ),
      $age);

    return array($link, $raw_age);
  }

}
