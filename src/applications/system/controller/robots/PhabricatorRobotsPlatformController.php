<?php

final class PhabricatorRobotsPlatformController
  extends PhabricatorRobotsController {

  protected function newRobotsRules() {
    $parts = array();

    $parts[] = array(
      'User-Agent: *',
    );

    $extensions = PhorgeRobotsTxtEngine::getAllExtensions();
    foreach ($extensions as $extension) {
      $parts[] = $extension->getRules();
    }

    // Add a small crawl delay (number of seconds between requests) for spiders
    // which respect it. The intent here is to prevent spiders from affecting
    // performance for users. The possible cost is slower indexing, but that
    // seems like a reasonable tradeoff, since most Phabricator installs are
    // probably not hugely concerned about cutting-edge SEO.
    $parts[] = array(
      'Crawl-delay: 1',
    );

    return array_mergev($parts);
  }

}
