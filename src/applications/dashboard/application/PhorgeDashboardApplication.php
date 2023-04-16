<?php

final class PhorgeDashboardApplication extends PhorgeApplication {

  public function getName() {
    return pht('Dashboards');
  }

  public function getBaseURI() {
    return '/dashboard/';
  }

  public function getTypeaheadURI() {
    return '/dashboard/console/';
  }

  public function getShortDescription() {
    return pht('Create Custom Pages');
  }

  public function getIcon() {
    return 'fa-dashboard';
  }

  public function isPinnedByDefault(PhorgeUser $viewer) {
    return true;
  }

  public function getApplicationOrder() {
    return 0.160;
  }

  public function getRoutes() {
    $menu_rules = $this->getProfileMenuRouting(
      'PhorgeDashboardPortalViewController');

    return array(
      '/W(?P<id>\d+)' => 'PhorgeDashboardPanelViewController',
      '/dashboard/' => array(
        '(?:query/(?P<queryKey>[^/]+)/)?'
          => 'PhorgeDashboardListController',
        'view/(?P<id>\d+)/' => 'PhorgeDashboardViewController',
        'archive/(?P<id>\d+)/' => 'PhorgeDashboardArchiveController',
        $this->getEditRoutePattern('edit/') =>
          'PhorgeDashboardEditController',
        'install/(?P<id>\d+)/'.
          '(?:(?P<workflowKey>[^/]+)/'.
          '(?:(?P<modeKey>[^/]+)/)?)?' =>
          'PhorgeDashboardInstallController',
        'console/' => 'PhorgeDashboardConsoleController',
        'adjust/(?P<op>remove|add|move)/'
          => 'PhorgeDashboardAdjustController',
        'panel/' => array(
          'install/(?P<engineKey>[^/]+)/(?:(?P<queryKey>[^/]+)/)?' =>
            'PhorgeDashboardQueryPanelInstallController',
          '(?:query/(?P<queryKey>[^/]+)/)?'
            => 'PhorgeDashboardPanelListController',
          $this->getEditRoutePattern('edit/')
            => 'PhorgeDashboardPanelEditController',
          'render/(?P<id>\d+)/' => 'PhorgeDashboardPanelRenderController',
          'archive/(?P<id>\d+)/'
            => 'PhorgeDashboardPanelArchiveController',
          'tabs/(?P<id>\d+)/(?P<op>add|move|remove|rename)/'
            => 'PhorgeDashboardPanelTabsController',
        ),
      ),
      '/portal/' => array(
        $this->getQueryRoutePattern() =>
          'PhorgeDashboardPortalListController',
        $this->getEditRoutePattern('edit/') =>
          'PhorgeDashboardPortalEditController',
        'view/(?P<portalID>\d+)/' => array(
            '' => 'PhorgeDashboardPortalViewController',
          ) + $menu_rules,

      ),
    );
  }

  public function getRemarkupRules() {
    return array(
      new PhorgeDashboardRemarkupRule(),
    );
  }

}
