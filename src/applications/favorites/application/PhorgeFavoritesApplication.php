<?php

final class PhorgeFavoritesApplication extends PhorgeApplication {

  public function getBaseURI() {
    return '/favorites/';
  }

  public function getName() {
    return pht('Favorites');
  }

  public function getShortDescription() {
    return pht('Favorite Items');
  }

  public function getIcon() {
    return 'fa-bookmark';
  }

  public function getRoutes() {
    return array(
      '/favorites/' => array(
        '' => 'PhorgeFavoritesMenuItemController',
        'menu/' => $this->getProfileMenuRouting(
          'PhorgeFavoritesMenuItemController'),
      ),
    );
  }

  public function isLaunchable() {
    return false;
  }

}
