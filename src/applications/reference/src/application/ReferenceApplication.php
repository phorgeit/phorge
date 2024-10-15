<?php

final class ReferenceApplication extends PhabricatorApplication {

  public function getName() {
    return pht('Reference');
  }

  public function getIcon() {
    return 'fa-code';
  }

  public function isUnlisted() {
    return true;
  }

  public function getRoutes() {
    return array(
      '/reference/' => array(
        'remarkup/' => 'RemarkupReferenceController',
        'cowsay/' => 'CowsayReferenceController',
        'figlet/' => 'FigletReferenceController',
      ),
    );
  }

}
