<?php

final class PhorgeSearchApplication extends PhorgeApplication {

  public function getBaseURI() {
    return '/search/';
  }

  public function getName() {
    return pht('Search');
  }

  public function getShortDescription() {
    return pht('Full-Text Search');
  }

  public function getFlavorText() {
    return pht('Find stuff in big piles.');
  }

  public function getIcon() {
    return 'fa-search';
  }

  public function isLaunchable() {
    return false;
  }

  public function getRoutes() {
    return array(
      '/search/' => array(
        '(?:query/(?P<queryKey>[^/]+)/)?' => 'PhorgeSearchController',
        'hovercard/'
          => 'PhorgeSearchHovercardController',
        'handle/(?P<phid>[^/]+)/'
          => 'PhorgeSearchHandleController',
        'edit/' => array(
          'key/(?P<queryKey>[^/]+)/' => 'PhorgeSearchEditController',
          'id/(?P<id>[^/]+)/' => 'PhorgeSearchEditController',
        ),
        'default/(?P<queryKey>[^/]+)/(?P<engine>[^/]+)/'
          => 'PhorgeSearchDefaultController',
        'delete/' => array(
          'key/(?P<queryKey>[^/]+)/(?P<engine>[^/]+)/'
            => 'PhorgeSearchDeleteController',
          'id/(?P<id>[^/]+)/'
            => 'PhorgeSearchDeleteController',
        ),
        'order/(?P<engine>[^/]+)/' => 'PhorgeSearchOrderController',
        'rel/(?P<relationshipKey>[^/]+)/(?P<sourcePHID>[^/]+)/'
          => 'PhorgeSearchRelationshipController',
        'source/(?P<relationshipKey>[^/]+)/(?P<sourcePHID>[^/]+)/'
          => 'PhorgeSearchRelationshipSourceController',
      ),
    );
  }

}
