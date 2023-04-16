<?php

final class PhorgeBadgesCommentController
  extends PhorgeBadgesController {

  public function handleRequest(AphrontRequest $request) {
    $viewer = $request->getViewer();
    $id = $request->getURIData('id');

    if (!$request->isFormPost()) {
      return new Aphront400Response();
    }

    $badge = id(new PhorgeBadgesQuery())
      ->setViewer($viewer)
      ->withIDs(array($id))
      ->executeOne();
    if (!$badge) {
      return new Aphront404Response();
    }

    $is_preview = $request->isPreviewRequest();
    $draft = PhorgeDraft::buildFromRequest($request);

    $view_uri = $this->getApplicationURI('view/'.$badge->getID());

    $xactions = array();
    $xactions[] = id(new PhorgeBadgesTransaction())
      ->setTransactionType(PhorgeTransactions::TYPE_COMMENT)
      ->attachComment(
        id(new PhorgeBadgesTransactionComment())
          ->setContent($request->getStr('comment')));

    $editor = id(new PhorgeBadgesEditor())
      ->setActor($viewer)
      ->setContinueOnNoEffect($request->isContinueRequest())
      ->setContentSourceFromRequest($request)
      ->setIsPreview($is_preview);

    try {
      $xactions = $editor->applyTransactions($badge, $xactions);
    } catch (PhorgeApplicationTransactionNoEffectException $ex) {
      return id(new PhorgeApplicationTransactionNoEffectResponse())
        ->setCancelURI($view_uri)
        ->setException($ex);
    }

    if ($draft) {
      $draft->replaceOrDelete();
    }

    if ($request->isAjax() && $is_preview) {
      return id(new PhorgeApplicationTransactionResponse())
        ->setObject($badge)
        ->setViewer($viewer)
        ->setTransactions($xactions)
        ->setIsPreview($is_preview);
    } else {
      return id(new AphrontRedirectResponse())
        ->setURI($view_uri);
    }
  }

}
