<?php

final class PhorgeRobotsBlogController
  extends PhorgeRobotsController {

  protected function newRobotsRules() {
    $out = array();

    // Allow everything on blog domains to be indexed.

    $out[] = 'User-Agent: *';
    $out[] = 'Crawl-delay: 1';

    return $out;
  }

}
