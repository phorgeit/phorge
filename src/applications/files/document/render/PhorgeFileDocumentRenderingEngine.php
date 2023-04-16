<?php

final class PhorgeFileDocumentRenderingEngine
  extends PhorgeDocumentRenderingEngine {

  protected function newRefViewURI(
    PhorgeDocumentRef $ref,
    PhorgeDocumentEngine $engine) {

    $file = $ref->getFile();
    $engine_key = $engine->getDocumentEngineKey();

    return urisprintf(
      '/file/view/%d/%s/',
      $file->getID(),
      $engine_key);
  }

  protected function newRefRenderURI(
    PhorgeDocumentRef $ref,
    PhorgeDocumentEngine $engine) {
    $file = $ref->getFile();
    if (!$file) {
      throw new PhutilMethodNotImplementedException();
    }

    $engine_key = $engine->getDocumentEngineKey();
    $file_phid = $file->getPHID();

    return urisprintf(
      '/file/document/%s/%s/',
      $engine_key,
      $file_phid);
  }

  protected function addApplicationCrumbs(
    PHUICrumbsView $crumbs,
    PhorgeDocumentRef $ref = null) {

    if ($ref) {
      $file = $ref->getFile();
      if ($file) {
        $crumbs->addTextCrumb($file->getMonogram(), $file->getInfoURI());
      }
    }

  }

}
