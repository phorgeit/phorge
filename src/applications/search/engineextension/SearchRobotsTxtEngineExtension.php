<?php

final class SearchRobotsTxtEngineExtension extends PhorgeRobotsTxtEngine {

  const EXTENSIONKEY = 'search';

  protected function getDisallowPaths() {
    return array(
      // Relationship search forms like '/search/rel/revision.has-task/' etc.
      '/search/rel/',
    );
  }

}
