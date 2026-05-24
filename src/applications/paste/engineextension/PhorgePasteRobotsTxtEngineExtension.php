<?php

final class PhorgePasteRobotsTxtEngineExtension extends PhorgeRobotsTxtEngine {

  const EXTENSIONKEY = 'paste';

  protected function getDisallowPaths() {

    // See T15662. Prevent indexing line anchor links in Pastes. Per RFC 9309
    // section 2.2.3, percentage-encode "$" to avoid interpretation as end of
    // match pattern. However, crawlers may not abide by it but follow the
    // original standard at https://www.robotstxt.org/orig.html with no mention
    // how to interpret characters like "$" and thus entirely ignore this rule.

    return array('/P*%24*');
  }

}
