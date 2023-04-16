<?php

/**
 * Callback provider for loading @{class@arcanist:ArcanistBundle} file data
 * stored in the Files application.
 */
final class PhorgeFileBundleLoader extends Phobject {

  private $viewer;

  public function setViewer(PhorgeUser $viewer) {
    $this->viewer = $viewer;
    return $this;
  }

  public function loadFileData($phid) {
    $file = id(new PhorgeFileQuery())
      ->setViewer($this->viewer)
      ->withPHIDs(array($phid))
      ->executeOne();
    if (!$file) {
      return null;
    }
    return $file->loadFileData();
  }

}
