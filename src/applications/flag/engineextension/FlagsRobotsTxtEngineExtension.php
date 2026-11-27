<?php

final class FlagsRobotsTxtEngineExtension extends PhorgeRobotsTxtEngine {

  const EXTENSIONKEY = 'flags';

  protected function getDisallowPaths() {
    return array(
      '/flag/delete/',
      '/flag/edit/',
    );
  }

}
