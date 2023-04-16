<?php

final class PhrictionMarkupPreviewController
  extends PhorgeController {

  public function handleRequest(AphrontRequest $request) {
    $viewer = $request->getViewer();

    $text = $request->getStr('text');
    $slug = $request->getStr('slug');

    $document = id(new PhrictionDocumentQuery())
      ->setViewer($viewer)
      ->withSlugs(array($slug))
      ->needContent(true)
      ->executeOne();
    if (!$document) {
      $document = PhrictionDocument::initializeNewDocument(
        $viewer,
        $slug);

      $content = id(new PhrictionContent())
        ->setSlug($slug);

      $document
        ->setPHID($document->generatePHID())
        ->attachContent($content);
    }

    $output = PhorgeMarkupEngine::renderOneObject(
      id(new PhorgeMarkupOneOff())
        ->setPreserveLinebreaks(true)
        ->setDisableCache(true)
        ->setContent($text),
      'default',
      $viewer,
      $document);

    return id(new AphrontAjaxResponse())
      ->setContent($output);
  }
}
