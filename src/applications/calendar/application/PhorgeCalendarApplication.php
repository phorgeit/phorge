<?php

final class PhorgeCalendarApplication extends PhorgeApplication {

  public function getName() {
    return pht('Calendar');
  }

  public function getShortDescription() {
    return pht('Upcoming Events');
  }

  public function getFlavorText() {
    return pht('Never miss an episode ever again.');
  }

  public function getBaseURI() {
    return '/calendar/';
  }

  public function getIcon() {
    return 'fa-calendar';
  }

  public function getTitleGlyph() {
    // Unicode has a calendar character but it's in some distant code plane,
    // use "keyboard" since it looks vaguely similar.
    return "\xE2\x8C\xA8";
  }

  public function getApplicationGroup() {
    return self::GROUP_UTILITIES;
  }

  public function isPrototype() {
    return true;
  }

  public function getRemarkupRules() {
    return array(
      new PhorgeCalendarRemarkupRule(),
    );
  }

  public function getRoutes() {
    return array(
      '/E(?P<id>[1-9]\d*)(?:/(?P<sequence>\d+)/)?'
        => 'PhorgeCalendarEventViewController',
      '/calendar/' => array(
        '(?:query/(?P<queryKey>[^/]+)/(?:(?P<year>\d+)/'.
          '(?P<month>\d+)/)?(?:(?P<day>\d+)/)?)?'
          => 'PhorgeCalendarEventListController',
        'event/' => array(
          $this->getEditRoutePattern('edit/')
            => 'PhorgeCalendarEventEditController',
          'drag/(?P<id>[1-9]\d*)/'
            => 'PhorgeCalendarEventDragController',
          'cancel/(?P<id>[1-9]\d*)/'
            => 'PhorgeCalendarEventCancelController',
          '(?P<action>join|decline|accept)/(?P<id>[1-9]\d*)/'
            => 'PhorgeCalendarEventJoinController',
          'export/(?P<id>[1-9]\d*)/(?P<filename>[^/]*)'
            => 'PhorgeCalendarEventExportController',
          'availability/(?P<id>[1-9]\d*)/(?P<availability>[^/]+)/'
            => 'PhorgeCalendarEventAvailabilityController',
        ),
        'export/' => array(
          $this->getQueryRoutePattern()
            => 'PhorgeCalendarExportListController',
          $this->getEditRoutePattern('edit/')
            => 'PhorgeCalendarExportEditController',
          '(?P<id>[1-9]\d*)/'
            => 'PhorgeCalendarExportViewController',
          'ics/(?P<secretKey>[^/]+)/(?P<filename>[^/]*)'
            => 'PhorgeCalendarExportICSController',
          'disable/(?P<id>[1-9]\d*)/'
            => 'PhorgeCalendarExportDisableController',
        ),
        'import/' => array(
          $this->getQueryRoutePattern()
            => 'PhorgeCalendarImportListController',
          $this->getEditRoutePattern('edit/')
            => 'PhorgeCalendarImportEditController',
          '(?P<id>[1-9]\d*)/'
            => 'PhorgeCalendarImportViewController',
          'disable/(?P<id>[1-9]\d*)/'
            => 'PhorgeCalendarImportDisableController',
          'delete/(?P<id>[1-9]\d*)/'
            => 'PhorgeCalendarImportDeleteController',
          'reload/(?P<id>[1-9]\d*)/'
            => 'PhorgeCalendarImportReloadController',
          'drop/'
            => 'PhorgeCalendarImportDropController',
          'log/' => array(
            $this->getQueryRoutePattern()
              => 'PhorgeCalendarImportLogListController',
          ),
        ),
      ),
    );
  }

  public function getHelpDocumentationArticles(PhorgeUser $viewer) {
    return array(
      array(
        'name' => pht('Calendar User Guide'),
        'href' => PhorgeEnv::getDoclink('Calendar User Guide'),
      ),
      array(
        'name' => pht('Importing Events'),
        'href' => PhorgeEnv::getDoclink(
          'Calendar User Guide: Importing Events'),
      ),
      array(
        'name' => pht('Exporting Events'),
        'href' => PhorgeEnv::getDoclink(
          'Calendar User Guide: Exporting Events'),
      ),
    );
  }

  public function getMailCommandObjects() {
    return array(
      'event' => array(
        'name' => pht('Email Commands: Events'),
        'header' => pht('Interacting with Calendar Events'),
        'object' => new PhorgeCalendarEvent(),
        'summary' => pht(
          'This page documents the commands you can use to interact with '.
          'events in Calendar. These commands work when creating new tasks '.
          'via email and when replying to existing tasks.'),
      ),
    );
  }

  protected function getCustomCapabilities() {
    return array(
      PhorgeCalendarEventDefaultViewCapability::CAPABILITY => array(
        'caption' => pht('Default view policy for newly created events.'),
        'template' => PhorgeCalendarEventPHIDType::TYPECONST,
        'capability' => PhorgePolicyCapability::CAN_VIEW,
      ),
      PhorgeCalendarEventDefaultEditCapability::CAPABILITY => array(
        'caption' => pht('Default edit policy for newly created events.'),
        'template' => PhorgeCalendarEventPHIDType::TYPECONST,
        'capability' => PhorgePolicyCapability::CAN_EDIT,
      ),
    );
  }

}
