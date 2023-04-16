<?php

final class PhorgeRemarkupDocumentEngine
  extends PhorgeDocumentEngine {

  const ENGINEKEY = 'remarkup';

  public function getViewAsLabel(PhorgeDocumentRef $ref) {
    return pht('View as Remarkup');
  }

  protected function getDocumentIconIcon(PhorgeDocumentRef $ref) {
    return 'fa-file-text-o';
  }

  protected function getContentScore(PhorgeDocumentRef $ref) {
    $name = $ref->getName();

    if ($name !== null) {
      if (preg_match('/\\.remarkup\z/i', $name)) {
        return 2000;
      }
    }

    return 500;
  }

  protected function canRenderDocumentType(PhorgeDocumentRef $ref) {
    return $ref->isProbablyText();
  }

  protected function newDocumentContent(PhorgeDocumentRef $ref) {
    $viewer = $this->getViewer();

    $content = $ref->loadData();
    $content = phutil_utf8ize($content);

    $remarkup = new PHUIRemarkupView($viewer, $content);

    $container = phutil_tag(
      'div',
      array(
        'class' => 'document-engine-remarkup',
      ),
      $remarkup);

    return $container;
  }

}
