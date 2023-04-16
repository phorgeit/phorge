<?php

final class PhorgeVoidDocumentEngine
  extends PhorgeDocumentEngine {

  const ENGINEKEY = 'void';

  public function getViewAsLabel(PhorgeDocumentRef $ref) {
    return null;
  }

  protected function getDocumentIconIcon(PhorgeDocumentRef $ref) {
    return 'fa-file';
  }

  protected function getContentScore(PhorgeDocumentRef $ref) {
    return 1000;
  }

  protected function getByteLengthLimit() {
    return null;
  }

  protected function canRenderDocumentType(PhorgeDocumentRef $ref) {
    return true;
  }

  protected function newDocumentContent(PhorgeDocumentRef $ref) {
    $message = pht(
      'No document engine can render the contents of this file.');

    $container = phutil_tag(
      'div',
      array(
        'class' => 'document-engine-message',
      ),
      $message);

    return $container;
  }

}
