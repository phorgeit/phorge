<?php

final class PhorgePeopleApplication extends PhorgeApplication {

  public function getName() {
    return pht('People');
  }

  public function getShortDescription() {
    return pht('User Accounts and Profiles');
  }

  public function getBaseURI() {
    return '/people/';
  }

  public function getTitleGlyph() {
    return "\xE2\x99\x9F";
  }

  public function getIcon() {
    return 'fa-users';
  }

  public function isPinnedByDefault(PhorgeUser $viewer) {
    return $viewer->getIsAdmin();
  }

  public function getFlavorText() {
    return pht('Sort of a social utility.');
  }

  public function getApplicationGroup() {
    return self::GROUP_UTILITIES;
  }

  public function canUninstall() {
    return false;
  }

  public function getRoutes() {
    return array(
      '/people/' => array(
        $this->getQueryRoutePattern() => 'PhorgePeopleListController',
        'logs/' => array(
          $this->getQueryRoutePattern() => 'PhorgePeopleLogsController',
          '(?P<id>\d+)/' => 'PhorgePeopleLogViewController',
        ),
        'invite/' => array(
          '(?:query/(?P<queryKey>[^/]+)/)?'
            => 'PhorgePeopleInviteListController',
          'send/'
            => 'PhorgePeopleInviteSendController',
        ),
        'approve/(?P<id>[1-9]\d*)/(?:via/(?P<via>[^/]+)/)?'
          => 'PhorgePeopleApproveController',
        '(?P<via>disapprove)/(?P<id>[1-9]\d*)/'
          => 'PhorgePeopleDisableController',
        '(?P<via>disable)/(?P<id>[1-9]\d*)/'
          => 'PhorgePeopleDisableController',
        'empower/(?P<id>[1-9]\d*)/' => 'PhorgePeopleEmpowerController',
        'delete/(?P<id>[1-9]\d*)/' => 'PhorgePeopleDeleteController',
        'rename/(?P<id>[1-9]\d*)/' => 'PhorgePeopleRenameController',
        'welcome/(?P<id>[1-9]\d*)/' => 'PhorgePeopleWelcomeController',
        'create/' => 'PhorgePeopleCreateController',
        'new/(?P<type>[^/]+)/' => 'PhorgePeopleNewController',
        'editprofile/(?P<id>[1-9]\d*)/' =>
          'PhorgePeopleProfileEditController',
        'badges/(?P<id>[1-9]\d*)/' =>
          'PhorgePeopleProfileBadgesController',
        'tasks/(?P<id>[1-9]\d*)/' =>
          'PhorgePeopleProfileTasksController',
        'commits/(?P<id>[1-9]\d*)/' =>
          'PhorgePeopleProfileCommitsController',
        'revisions/(?P<id>[1-9]\d*)/' =>
          'PhorgePeopleProfileRevisionsController',
        'picture/(?P<id>[1-9]\d*)/' =>
          'PhorgePeopleProfilePictureController',
        'manage/(?P<id>[1-9]\d*)/' =>
          'PhorgePeopleProfileManageController',
      ),
      '/p/(?P<username>[\w._-]+)/' => array(
        '' => 'PhorgePeopleProfileViewController',
      ),
    );
  }

  public function getRemarkupRules() {
    return array(
      new PhorgeMentionRemarkupRule(),
    );
  }

  protected function getCustomCapabilities() {
    return array(
      PeopleCreateUsersCapability::CAPABILITY => array(
        'default' => PhorgePolicies::POLICY_ADMIN,
      ),
      PeopleDisableUsersCapability::CAPABILITY => array(
        'default' => PhorgePolicies::POLICY_ADMIN,
      ),
      PeopleBrowseUserDirectoryCapability::CAPABILITY => array(),
    );
  }

  public function getApplicationSearchDocumentTypes() {
    return array(
      PhorgePeopleUserPHIDType::TYPECONST,
    );
  }

}
