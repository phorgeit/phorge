<?php

final class PhorgeTransactionsApplication extends PhorgeApplication {

  public function getName() {
    return pht('Transactions');
  }

  public function isLaunchable() {
    return false;
  }

  public function canUninstall() {
    return false;
  }

  public function getRoutes() {
    return array(
      '/transactions/' => array(
        'edit/(?<phid>[^/]+)/'
          => 'PhorgeApplicationTransactionCommentEditController',
        'remove/(?<phid>[^/]+)/'
          => 'PhorgeApplicationTransactionCommentRemoveController',
        'history/(?<phid>[^/]+)/'
          => 'PhorgeApplicationTransactionCommentHistoryController',
        'quote/(?<phid>[^/]+)/'
          => 'PhorgeApplicationTransactionCommentQuoteController',
        'raw/(?<phid>[^/]+)/'
          => 'PhorgeApplicationTransactionCommentRawController',
        'detail/(?<phid>[^/]+)/'
          => 'PhorgeApplicationTransactionDetailController',
        'showolder/(?<phid>[^/]+)/'
          => 'PhorgeApplicationTransactionShowOlderController',
        '(?P<value>old|new)/(?<phid>[^/]+)/'
          => 'PhorgeApplicationTransactionValueController',
        'remarkuppreview/'
          => 'PhorgeApplicationTransactionRemarkupPreviewController',
        'editengine/' => array(
          $this->getQueryRoutePattern()
            => 'PhorgeEditEngineListController',
          '(?P<engineKey>[^/]+)/' => array(
            $this->getQueryRoutePattern() =>
              'PhorgeEditEngineConfigurationListController',
            $this->getEditRoutePattern('edit/') =>
              'PhorgeEditEngineConfigurationEditController',
            'sort/(?P<type>edit|create)/' =>
              'PhorgeEditEngineConfigurationSortController',
            'view/(?P<key>[^/]+)/' =>
              'PhorgeEditEngineConfigurationViewController',
            'save/(?P<key>[^/]+)/' =>
              'PhorgeEditEngineConfigurationSaveController',
            'reorder/(?P<key>[^/]+)/' =>
              'PhorgeEditEngineConfigurationReorderController',
            'defaults/(?P<key>[^/]+)/' =>
              'PhorgeEditEngineConfigurationDefaultsController',
            'lock/(?P<key>[^/]+)/' =>
              'PhorgeEditEngineConfigurationLockController',
            'subtype/(?P<key>[^/]+)/' =>
              'PhorgeEditEngineConfigurationSubtypeController',
            'defaultcreate/(?P<key>[^/]+)/' =>
              'PhorgeEditEngineConfigurationDefaultCreateController',
            'defaultedit/(?P<key>[^/]+)/' =>
              'PhorgeEditEngineConfigurationIsEditController',
            'disable/(?P<key>[^/]+)/' =>
              'PhorgeEditEngineConfigurationDisableController',
          ),
        ),
      ),
    );
  }

}
