<?php

final class PhabricatorRobotsPlatformController
  extends PhabricatorRobotsController {

  protected function newRobotsRules() {
    $out = array();

    // Prevent indexing of '/diffusion/', since the content is not generally
    // useful to index, web spiders get stuck scraping the history of every
    // file, and much of the content is Ajaxed in anyway so spiders won't even
    // see it. These pages are also relatively expensive to generate.

    // Note that this still allows commits (at '/rPxxxxx') to be indexed.
    // They're probably not hugely useful, but suffer fewer of the problems
    // Diffusion suffers and are hard to omit with 'robots.txt'.

    $out[] = 'User-Agent: *';
    $out[] = 'Disallow: /diffusion/';
    $out[] = 'Disallow: /source/';
    // See T15670. Also prevent directly accessing commits in Diffusion.
    $out[] = 'Disallow: /r*';

    // See T15662. Prevent indexing line anchor links in Pastes. Per RFC 9309
    // section 2.2.3, percentage-encode "$" to avoid interpretation as end of
    // match pattern. However, crawlers may not abide by it but follow the
    // original standard at https://www.robotstxt.org/orig.html with no mention
    // how to interpret characters like "$" and thus entirely ignore this rule.
    $out[] = 'Disallow: /P*%24*';

    // Add a small crawl delay (number of seconds between requests) for spiders
    // which respect it. The intent here is to prevent spiders from affecting
    // performance for users. The possible cost is slower indexing, but that
    // seems like a reasonable tradeoff, since most Phabricator installs are
    // probably not hugely concerned about cutting-edge SEO.
    $out[] = 'Crawl-delay: 1';

    return $out;
  }

}
