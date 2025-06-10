<?php

final class PHUICalendarDayView extends AphrontView {
  private $rangeStart;
  private $rangeEnd;

  private $day;
  private $month;
  private $year;
  private $browseURI;
  private $query;
  private $events = array();

  private $allDayEvents = array();

  public function addEvent(AphrontCalendarEventView $event) {
    $this->events[] = $event;
    return $this;
  }

  public function setBrowseURI($browse_uri) {
    $this->browseURI = $browse_uri;
    return $this;
  }
  private function getBrowseURI() {
    return $this->browseURI;
  }

  public function setQuery($query) {
    $this->query = $query;
    return $this;
  }
  private function getQuery() {
    return $this->query;
  }

  public function __construct(
    $range_start,
    $range_end,
    $year,
    $month,
    $day = null) {

    $this->rangeStart = $range_start;
    $this->rangeEnd = $range_end;

    $this->day = $day;
    $this->month = $month;
    $this->year = $year;
  }

  public function render() {
    require_celerity_resource('phui-calendar-day-css');

    $viewer = $this->getUser();

    $hours = $this->getHoursOfDay();
    $js_hours = array();
    $js_today_events = array();

    foreach ($hours as $hour) {
      $js_hours[] = array(
        'hour' => $hour->format('G'),
        'displayTime' => vixon_time($hour->format('U'), $viewer),
      );
    }

    $first_event_hour = null;
    $js_today_all_day_events = array();
    $all_day_events = $this->getAllDayEvents();

    $day_start = $this->getDateTime();
    $day_end = id(clone $day_start)->modify('+1 day');

    $day_start_epoch = $day_start->format('U');
    $day_end_epoch = $day_end->format('U') - 1;

    foreach ($all_day_events as $all_day_event) {
      $all_day_start = $all_day_event->getEpochStart();
      $all_day_end = $all_day_event->getEpochEnd();

      if ($all_day_start < $day_end_epoch && $all_day_end > $day_start_epoch) {
        $js_today_all_day_events[] = array(
          'name' => $all_day_event->getName(),
          'id' => $all_day_event->getEventID(),
          'viewerIsInvited' => $all_day_event->getViewerIsInvited(),
          'uri' => $all_day_event->getURI(),
          'displayIcon' => $all_day_event->getIcon(),
          'displayIconColor' => $all_day_event->getIconColor(),
        );
      }
    }

    $this->events = msort($this->events, 'getEpochStart');
    $first_event_hour = $this->getDateTime()->setTime(8, 0, 0);
    $midnight = $this->getDateTime()->setTime(0, 0, 0);

    foreach ($this->events as $event) {
      if ($event->getIsAllDay()) {
        continue;
      }
      if ($event->getEpochStart() <= $day_end_epoch &&
        $event->getEpochEnd() > $day_start_epoch) {

        if ($event->getEpochStart() < $midnight->format('U') &&
          $event->getEpochEnd() > $midnight->format('U')) {
          $first_event_hour = clone $midnight;
        }

        if ($event->getEpochStart() < $first_event_hour->format('U') &&
          $event->getEpochStart() > $midnight->format('U')) {
          $first_event_hour = PhabricatorTime::getDateTimeFromEpoch(
            $event->getEpochStart(),
            $viewer);
          $first_event_hour->setTime($first_event_hour->format('h'), 0, 0);
        }

        $event_start = max($event->getEpochStart(), $day_start_epoch);
        $event_end = min($event->getEpochEnd(), $day_end_epoch);

        $day_duration = ($day_end_epoch - $first_event_hour->format('U')) / 60;

        $top = (($event_start - $first_event_hour->format('U'))
          / ($day_end_epoch - $first_event_hour->format('U')))
          * $day_duration;
        $top = max(0, $top);

        $height = (($event_end - $event_start)
          / ($day_end_epoch - $first_event_hour->format('U')))
          * $day_duration;
        $height = min($day_duration, $height);

        $js_today_events[] = array(
          'eventStartEpoch' => $event->getEpochStart(),
          'eventEndEpoch' => $event->getEpochEnd(),
          'eventName' => $event->getName(),
          'eventID' => $event->getEventID(),
          'viewerIsInvited' => $event->getViewerIsInvited(),
          'uri' => $event->getURI(),
          'offset' => '0',
          'width' => '100%',
          'top' => $top.'px',
          'height' => $height.'px',
          'canEdit' => $event->getCanEdit(),
          'displayIcon' => $event->getIcon(),
          'displayIconColor' => $event->getIconColor(),
        );
      }
    }

    $header = $this->renderDayViewHeader();
    $sidebar = id(new PHUICalendarWeekView())
      ->setViewer($this->getViewer())
      ->setEvents($this->events)
      ->setDateTime($this->getDateTime())
      ->render();
    $warnings = $this->getQueryRangeWarning();

    $table_id = celerity_generate_unique_node_id();

    $table_wrapper = phutil_tag(
      'div',
      array(
        'id' => $table_id,
      ),
      '');

    Javelin::initBehavior(
      'day-view',
      array(
        'year' => $first_event_hour->format('Y'),
        'month' => $first_event_hour->format('m'),
        'day' => $first_event_hour->format('d'),
        'query' => $this->getQuery(),
        'allDayEvents' => $js_today_all_day_events,
        'todayEvents' => $js_today_events,
        'hours' => $js_hours,
        'firstEventHour' => $first_event_hour->format('G'),
        'firstEventHourEpoch' => $first_event_hour->format('U'),
        'tableID' => $table_id,
      ));

    $table_box = id(new PHUIObjectBoxView())
      ->setHeader($header)
      ->appendChild($table_wrapper)
      ->setFormErrors($warnings)
      ->setFlush(true);

    $layout = id(new AphrontMultiColumnView())
      ->addColumn($sidebar, 'third phui-day-view-upcoming')
      ->addColumn($table_box, 'thirds phui-day-view-column')
      ->setFluidLayout(true);

    $layout = id(new PHUIBoxView())
      ->appendChild($layout)
      ->addClass('phui-calendar-box');

    return $layout;
  }

  private function getAllDayEvents() {
    $all_day_events = array();

    foreach ($this->events as $event) {
      if ($event->getIsAllDay()) {
        $all_day_events[] = $event;
      }
    }

    $all_day_events = array_values(msort($all_day_events, 'getEpochStart'));
    return $all_day_events;
  }

  private function getQueryRangeWarning() {
    $errors = array();

    $range_start_epoch = null;
    $range_end_epoch = null;

    if ($this->rangeStart) {
      $range_start_epoch = $this->rangeStart->getEpoch();
    }
    if ($this->rangeEnd) {
      $range_end_epoch = $this->rangeEnd->getEpoch();
    }

    $day_start = $this->getDateTime();
    $day_end = id(clone $day_start)->modify('+1 day');

    $day_start = $day_start->format('U');
    $day_end = $day_end->format('U') - 1;

    if (($range_start_epoch != null &&
        $range_start_epoch < $day_end &&
        $range_start_epoch > $day_start) ||
      ($range_end_epoch != null &&
        $range_end_epoch < $day_end &&
        $range_end_epoch > $day_start)) {
      $errors[] = pht('Part of the day is out of range');
    }

    if (($range_end_epoch != null &&
        $range_end_epoch < $day_start) ||
      ($range_start_epoch != null &&
        $range_start_epoch > $day_end)) {
      $errors[] = pht('Day is out of query range');
    }
    return $errors;
  }

  private function renderDayViewHeader() {
    $button_bar = null;
    $uri = $this->getBrowseURI();
    if ($uri) {
      list($prev_year, $prev_month, $prev_day) = $this->getPrevDay();
      $prev_uri = $uri.$prev_year.'/'.$prev_month.'/'.$prev_day.'/';

      list($next_year, $next_month, $next_day) = $this->getNextDay();
      $next_uri = $uri.$next_year.'/'.$next_month.'/'.$next_day.'/';

      $button_bar = new PHUIButtonBarView();

      $left_icon = id(new PHUIIconView())
          ->setIcon('fa-chevron-left bluegrey');
      $left = id(new PHUIButtonView())
        ->setTag('a')
        ->setColor(PHUIButtonView::GREY)
        ->setHref($prev_uri)
        ->setTitle(pht('Previous Day'))
        ->setIcon($left_icon);

      $right_icon = id(new PHUIIconView())
          ->setIcon('fa-chevron-right bluegrey');
      $right = id(new PHUIButtonView())
        ->setTag('a')
        ->setColor(PHUIButtonView::GREY)
        ->setHref($next_uri)
        ->setTitle(pht('Next Day'))
        ->setIcon($right_icon);

      $button_bar->addButton($left);
      $button_bar->addButton($right);

    }

    $display_day = $this->getDateTime();
    $header_text = $display_day->format('l, F j, Y');

    $header = id(new PHUIHeaderView())
      ->setHeader($header_text);

    if ($button_bar) {
      $header->setButtonBar($button_bar);
    }

    return $header;
  }

  // returns DateTime of each hour in the day
  private function getHoursOfDay() {
    $included_datetimes = array();

    $day_datetime = $this->getDateTime();
    $day_epoch = $day_datetime->format('U');

    $day_datetime->modify('+1 day');
    $next_day_epoch = $day_datetime->format('U');

    $included_time = $day_epoch;
    $included_datetime = $this->getDateTime();

    while ($included_time < $next_day_epoch) {
      $included_datetimes[] = clone $included_datetime;

      $included_datetime->modify('+1 hour');
      $included_time = $included_datetime->format('U');
    }

    return $included_datetimes;
  }

  private function getPrevDay() {
    $prev = $this->getDateTime();
    $prev->modify('-1 day');
    return array(
      $prev->format('Y'),
      $prev->format('m'),
      $prev->format('d'),
    );
  }

  private function getNextDay() {
    $next = $this->getDateTime();
    $next->modify('+1 day');
    return array(
      $next->format('Y'),
      $next->format('m'),
      $next->format('d'),
    );
  }

  private function getDateTime() {
    $user = $this->getViewer();
    $timezone = new DateTimeZone($user->getTimezoneIdentifier());

    $day = $this->day;
    $month = $this->month;
    $year = $this->year;

    $date = new DateTime("{$year}-{$month}-{$day} ", $timezone);

    return $date;
  }
}
