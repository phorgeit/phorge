<?php

final class PhorgeProjectApplication extends PhorgeApplication {

  public function getName() {
    return pht('Projects');
  }

  public function getShortDescription() {
    return pht('Projects, Tags, and Teams');
  }

  public function isPinnedByDefault(PhorgeUser $viewer) {
    return true;
  }

  public function getBaseURI() {
    return '/project/';
  }

  public function getIcon() {
    return 'fa-briefcase';
  }

  public function getFlavorText() {
    return pht('Group stuff into big piles.');
  }

  public function getRemarkupRules() {
    return array(
      new ProjectRemarkupRule(),
    );
  }

  public function getEventListeners() {
    return array(
      new PhorgeProjectUIEventListener(),
    );
  }

  public function getRoutes() {
    return array(
      '/project/' => array(
        '(?:query/(?P<queryKey>[^/]+)/)?' => 'PhorgeProjectListController',
        'filter/(?P<filter>[^/]+)/' => 'PhorgeProjectListController',
        'archive/(?P<id>[1-9]\d*)/'
          => 'PhorgeProjectArchiveController',
        'lock/(?P<id>[1-9]\d*)/'
          => 'PhorgeProjectLockController',
        'members/(?P<id>[1-9]\d*)/'
          => 'PhorgeProjectMembersViewController',
        'members/(?P<id>[1-9]\d*)/add/'
          => 'PhorgeProjectMembersAddController',
        '(?P<type>members|watchers)/(?P<id>[1-9]\d*)/remove/'
          => 'PhorgeProjectMembersRemoveController',
        'profile/(?P<id>[1-9]\d*)/'
          => 'PhorgeProjectProfileController',
        'view/(?P<id>[1-9]\d*)/'
          => 'PhorgeProjectViewController',
        'picture/(?P<id>[1-9]\d*)/'
          => 'PhorgeProjectEditPictureController',
        $this->getEditRoutePattern('edit/')
          => 'PhorgeProjectEditController',
        '(?P<projectID>[1-9]\d*)/item/' => $this->getProfileMenuRouting(
          'PhorgeProjectMenuItemController'),
        'subprojects/(?P<id>[1-9]\d*)/'
          => 'PhorgeProjectSubprojectsController',
        'board/(?P<id>[1-9]\d*)/'.
          '(?:query/(?P<queryKey>[^/]+)/)?'
          => 'PhorgeProjectBoardViewController',
        'move/(?P<id>[1-9]\d*)/' => 'PhorgeProjectMoveController',
        'cover/' => 'PhorgeProjectCoverController',
        'reports/(?P<projectID>[1-9]\d*)/' =>
          'PhorgeProjectReportsController',
        'board/(?P<projectID>[1-9]\d*)/' => array(
          'edit/(?:(?P<id>\d+)/)?'
            => 'PhorgeProjectColumnEditController',
          'hide/(?:(?P<id>\d+)/)?'
            => 'PhorgeProjectColumnHideController',
          'column/(?:(?P<id>\d+)/)?'
            => 'PhorgeProjectColumnDetailController',
          'viewquery/(?P<columnID>\d+)/'
            => 'PhorgeProjectColumnViewQueryController',
          'bulk/(?P<columnID>\d+)/'
            => 'PhorgeProjectColumnBulkEditController',
          'bulkmove/(?P<columnID>\d+)/(?P<mode>project|column)/'
            => 'PhorgeProjectColumnBulkMoveController',
          'import/'
            => 'PhorgeProjectBoardImportController',
          'reorder/'
            => 'PhorgeProjectBoardReorderController',
          'disable/'
            => 'PhorgeProjectBoardDisableController',
          'manage/'
            => 'PhorgeProjectBoardManageController',
          'background/'
            => 'PhorgeProjectBoardBackgroundController',
          'default/(?P<target>[^/]+)/'
            => 'PhorgeProjectBoardDefaultController',
          'filter/(?:query/(?P<queryKey>[^/]+)/)?'
            => 'PhorgeProjectBoardFilterController',
          'reload/'
            => 'PhorgeProjectBoardReloadController',
        ),
        'column/' => array(
          'remove/(?P<id>\d+)/' =>
            'PhorgeProjectColumnRemoveTriggerController',
        ),
        'trigger/' => array(
          $this->getQueryRoutePattern() =>
            'PhorgeProjectTriggerListController',
          '(?P<id>[1-9]\d*)/' =>
            'PhorgeProjectTriggerViewController',
          $this->getEditRoutePattern('edit/') =>
            'PhorgeProjectTriggerEditController',
        ),
        'update/(?P<id>[1-9]\d*)/(?P<action>[^/]+)/'
          => 'PhorgeProjectUpdateController',
        'manage/(?P<id>[1-9]\d*)/' => 'PhorgeProjectManageController',
        '(?P<action>watch|unwatch)/(?P<id>[1-9]\d*)/'
          => 'PhorgeProjectWatchController',
        'silence/(?P<id>[1-9]\d*)/'
          => 'PhorgeProjectSilenceController',
        'warning/(?P<id>[1-9]\d*)/'
          => 'PhorgeProjectSubprojectWarningController',
      ),
      '/tag/' => array(
        '(?P<slug>[^/]+)/' => 'PhorgeProjectViewController',
        '(?P<slug>[^/]+)/board/' => 'PhorgeProjectBoardViewController',
      ),
    );
  }

  protected function getCustomCapabilities() {
    return array(
      ProjectCreateProjectsCapability::CAPABILITY => array(),
      ProjectCanLockProjectsCapability::CAPABILITY => array(
        'default' => PhorgePolicies::POLICY_ADMIN,
      ),
      ProjectDefaultViewCapability::CAPABILITY => array(
        'caption' => pht('Default view policy for newly created projects.'),
        'template' => PhorgeProjectProjectPHIDType::TYPECONST,
        'capability' => PhorgePolicyCapability::CAN_VIEW,
      ),
      ProjectDefaultEditCapability::CAPABILITY => array(
        'caption' => pht('Default edit policy for newly created projects.'),
        'template' => PhorgeProjectProjectPHIDType::TYPECONST,
        'capability' => PhorgePolicyCapability::CAN_EDIT,
      ),
      ProjectDefaultJoinCapability::CAPABILITY => array(
        'caption' => pht('Default join policy for newly created projects.'),
        'template' => PhorgeProjectProjectPHIDType::TYPECONST,
        'capability' => PhorgePolicyCapability::CAN_JOIN,
      ),
    );
  }

  public function getApplicationSearchDocumentTypes() {
    return array(
      PhorgeProjectProjectPHIDType::TYPECONST,
    );
  }

  public function getApplicationOrder() {
    return 0.150;
  }

  public function getHelpDocumentationArticles(PhorgeUser $viewer) {
    return array(
      array(
        'name' => pht('Projects User Guide'),
        'href' => PhorgeEnv::getDoclink('Projects User Guide'),
      ),
    );
  }

}
