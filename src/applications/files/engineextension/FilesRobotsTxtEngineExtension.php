<?php

final class FilesRobotsTxtEngineExtension extends PhorgeRobotsTxtEngine {

  const EXTENSIONKEY = 'files';

  protected function getDisallowPaths() {
    return array(
      '/file/delete/',
      '/file/document/image/',
      '/file/document/hexdump/',
      '/file/edit/',
      '/file/xform/*?regenerate=true',
    );
  }

}
