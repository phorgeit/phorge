<?php

final class PhorgeHelpApplication extends PhorgeApplication {

  public function getName() {
    return pht('Help');
  }

  public function canUninstall() {
    return false;
  }

  public function isUnlisted() {
    return true;
  }

  public function getRoutes() {
    return array(
      '/help/' => array(
        'keyboardshortcut/' => 'PhorgeHelpKeyboardShortcutController',
        'documentation/(?P<application>\w+)/'
          => 'PhorgeHelpDocumentationController',
      ),
    );
  }

}
