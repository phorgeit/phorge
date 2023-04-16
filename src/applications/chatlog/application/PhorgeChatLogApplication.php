<?php

final class PhorgeChatLogApplication extends PhorgeApplication {

  public function getBaseURI() {
    return '/chatlog/';
  }

  public function getName() {
    return pht('ChatLog');
  }

  public function getShortDescription() {
    return pht('(Deprecated)');
  }

  public function getIcon() {
    return 'fa-coffee';
  }

  public function isPrototype() {
    return true;
  }

  public function getTitleGlyph() {
    return "\xE0\xBC\x84";
  }

  public function getApplicationGroup() {
    return self::GROUP_UTILITIES;
  }

 public function getRoutes() {
    return array(
      '/chatlog/' => array(
       '' => 'PhorgeChatLogChannelListController',
       'channel/(?P<channelID>[^/]+)/'
          => 'PhorgeChatLogChannelLogController',
       ),
    );
  }

}
