<?php

final class PhorgeSlowvoteApplication extends PhorgeApplication {

  public function getBaseURI() {
    return '/vote/';
  }

  public function getIcon() {
    return 'fa-bar-chart';
  }

  public function getName() {
    return pht('Slowvote');
  }

  public function getShortDescription() {
    return pht('Conduct Polls');
  }

  public function getTitleGlyph() {
    return "\xE2\x9C\x94";
  }

  public function getHelpDocumentationArticles(PhorgeUser $viewer) {
    return array(
      array(
        'name' => pht('Slowvote User Guide'),
        'href' => PhorgeEnv::getDoclink('Slowvote User Guide'),
      ),
    );
  }

  public function getFlavorText() {
    return pht('Design by committee.');
  }

  public function getApplicationGroup() {
    return self::GROUP_UTILITIES;
  }

  public function getRemarkupRules() {
    return array(
      new SlowvoteRemarkupRule(),
    );
  }

  public function getRoutes() {
    return array(
      '/V(?P<id>[1-9]\d*)' => 'PhorgeSlowvotePollController',
      '/vote/' => array(
        '(?:query/(?P<queryKey>[^/]+)/)?'
          => 'PhorgeSlowvoteListController',
        'create/' => 'PhorgeSlowvoteEditController',
        'edit/(?P<id>[1-9]\d*)/' => 'PhorgeSlowvoteEditController',
        '(?P<id>[1-9]\d*)/' => 'PhorgeSlowvoteVoteController',
        'comment/(?P<id>[1-9]\d*)/' => 'PhorgeSlowvoteCommentController',
        'close/(?P<id>[1-9]\d*)/' => 'PhorgeSlowvoteCloseController',
      ),
    );
  }

  protected function getCustomCapabilities() {
    return array(
      PhorgeSlowvoteDefaultViewCapability::CAPABILITY => array(
        'caption' => pht('Default view policy for new polls.'),
        'template' => PhorgeSlowvotePollPHIDType::TYPECONST,
        'capability' => PhorgePolicyCapability::CAN_VIEW,
      ),
    );
  }

}
