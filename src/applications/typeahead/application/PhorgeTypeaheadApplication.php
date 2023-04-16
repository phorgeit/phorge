<?php

final class PhorgeTypeaheadApplication extends PhorgeApplication {

  public function getName() {
    return pht('Typeahead');
  }

  public function getRoutes() {
    return array(
      '/typeahead/' => array(
        '(?P<action>browse|class)/(?:(?P<class>\w+)/)?'
          => 'PhorgeTypeaheadModularDatasourceController',
        'help/(?P<class>\w+)/'
          => 'PhorgeTypeaheadFunctionHelpController',
      ),
    );
  }

  public function isLaunchable() {
    return false;
  }

  public function canUninstall() {
    return false;
  }

}
