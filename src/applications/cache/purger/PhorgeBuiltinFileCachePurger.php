<?php

final class PhorgeBuiltinFileCachePurger
  extends PhorgeCachePurger {

  const PURGERKEY = 'builtin-file';

  public function purgeCache() {
    $viewer = $this->getViewer();

    $files = id(new PhorgeFileQuery())
      ->setViewer($viewer)
      ->withIsBuiltin(true)
      ->execute();

    $engine = new PhorgeDestructionEngine();
    foreach ($files as $file) {
      $engine->destroyObject($file);
    }
  }

}
