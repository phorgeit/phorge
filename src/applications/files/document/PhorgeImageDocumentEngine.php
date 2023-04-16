<?php

final class PhorgeImageDocumentEngine
  extends PhorgeDocumentEngine {

  const ENGINEKEY = 'image';

  public function getViewAsLabel(PhorgeDocumentRef $ref) {
    return pht('View as Image');
  }

  protected function getDocumentIconIcon(PhorgeDocumentRef $ref) {
    return 'fa-file-image-o';
  }

  protected function getByteLengthLimit() {
    return (1024 * 1024 * 64);
  }

  public function canDiffDocuments(
    PhorgeDocumentRef $uref = null,
    PhorgeDocumentRef $vref = null) {

    // For now, we can only render a rich image diff if the documents have
    // their data stored in Files already.

    if ($uref && !$uref->getFile()) {
      return false;
    }

    if ($vref && !$vref->getFile()) {
      return false;
    }

    return true;
  }

  public function newEngineBlocks(
    PhorgeDocumentRef $uref = null,
    PhorgeDocumentRef $vref = null) {

    if ($uref) {
      $u_blocks = $this->newDiffBlocks($uref);
    } else {
      $u_blocks = array();
    }

    if ($vref) {
      $v_blocks = $this->newDiffBlocks($vref);
    } else {
      $v_blocks = array();
    }

    return id(new PhorgeDocumentEngineBlocks())
      ->addBlockList($uref, $u_blocks)
      ->addBlockList($vref, $v_blocks);
  }

  public function newBlockDiffViews(
    PhorgeDocumentRef $uref,
    PhorgeDocumentEngineBlock $ublock,
    PhorgeDocumentRef $vref,
    PhorgeDocumentEngineBlock $vblock) {

    $u_content = $this->newBlockContentView($uref, $ublock);
    $v_content = $this->newBlockContentView($vref, $vblock);

    return id(new PhorgeDocumentEngineBlockDiff())
      ->setOldContent($u_content)
      ->addOldClass('diff-image-cell')
      ->setNewContent($v_content)
      ->addNewClass('diff-image-cell');
  }


  private function newDiffBlocks(PhorgeDocumentRef $ref) {
    $blocks = array();

    $file = $ref->getFile();

    $image_view = phutil_tag(
      'div',
      array(
        'class' => 'differential-image-stage',
      ),
      phutil_tag(
        'img',
        array(
          'alt' => $file->getAltText(),
          'src' => $file->getBestURI(),
        )));

    $hash = $file->getContentHash();

    $blocks[] = id(new PhorgeDocumentEngineBlock())
      ->setBlockKey('1')
      ->setDifferenceHash($hash)
      ->setContent($image_view);

    return $blocks;
  }

  protected function canRenderDocumentType(PhorgeDocumentRef $ref) {
    $file = $ref->getFile();
    if ($file) {
      return $file->isViewableImage();
    }

    $viewable_types = PhorgeEnv::getEnvConfig('files.viewable-mime-types');
    $viewable_types = array_keys($viewable_types);

    $image_types = PhorgeEnv::getEnvConfig('files.image-mime-types');
    $image_types = array_keys($image_types);

    return
      $ref->hasAnyMimeType($viewable_types) &&
      $ref->hasAnyMimeType($image_types);
  }

  protected function newDocumentContent(PhorgeDocumentRef $ref) {
    $file = $ref->getFile();
    if ($file) {
      $source_uri = $file->getViewURI();
    } else {
      // We could use a "data:" URI here. It's not yet clear if or when we'll
      // have a ref but no backing file.
      throw new PhutilMethodNotImplementedException();
    }

    $image = phutil_tag(
      'img',
      array(
        'alt' => $file->getAltText(),
        'src' => $source_uri,
      ));

    $linked_image = phutil_tag(
      'a',
      array(
        'href' => $source_uri,
        'rel' => 'noreferrer',
      ),
      $image);

    $container = phutil_tag(
      'div',
      array(
        'class' => 'document-engine-image',
      ),
      $linked_image);

    return $container;
  }

}
