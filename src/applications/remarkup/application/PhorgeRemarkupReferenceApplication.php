<?php

final class PhorgeRemarkupReferenceApplication extends PhabricatorApplication {

  public function getName() {
    return pht('Reference');
  }

  public function getIcon() {
    return 'fa-code';
  }

  public function isUnlisted() {
    return true;
  }

  public function getBaseURI() {
    return '/remarkup/';
  }

  public function getRoutes() {
    return array(
      '/remarkup/' => array(
        '' => PhorgeRemarkupReferenceModuleController::class,
        '(?P<module>[^/]+)/' => PhorgeRemarkupReferenceModuleController::class,
        'rule/(?P<class>[^/]+)/' =>
          PhorgeRemarkupReferenceRuleController::class,
      ),
      '/reference/.*' => RedirectOldRemarkupReferenceController::class,
    );
  }

}
