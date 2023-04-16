<?php

final class PhorgeFileLightboxController
  extends PhorgeFileController {

  public function shouldAllowPublic() {
    return true;
  }

  public function handleRequest(AphrontRequest $request) {
    $viewer = $request->getViewer();
    $phid = $request->getURIData('phid');
    $comment = $request->getStr('comment');

    $file = id(new PhorgeFileQuery())
      ->setViewer($viewer)
      ->withPHIDs(array($phid))
      ->executeOne();
    if (!$file) {
      return new Aphront404Response();
    }

    if (strlen($comment)) {
      $xactions = array();
      $xactions[] = id(new PhorgeFileTransaction())
        ->setTransactionType(PhorgeTransactions::TYPE_COMMENT)
        ->attachComment(
          id(new PhorgeFileTransactionComment())
            ->setContent($comment));

      $editor = id(new PhorgeFileEditor())
        ->setActor($viewer)
        ->setContinueOnNoEffect(true)
        ->setContentSourceFromRequest($request);

      $editor->applyTransactions($file, $xactions);
    }

    $transactions = id(new PhorgeFileTransactionQuery())
      ->withTransactionTypes(array(PhorgeTransactions::TYPE_COMMENT));
    $timeline = $this->buildTransactionTimeline($file, $transactions);

    $comment_form = $this->renderCommentForm($file);

    $info = phutil_tag(
      'div',
      array(
        'class' => 'phui-comment-panel-header',
      ),
      $file->getName());

    require_celerity_resource('phui-comment-panel-css');
    $content = phutil_tag(
      'div',
      array(
        'class' => 'phui-comment-panel',
      ),
      array(
        $info,
        $timeline,
        $comment_form,
      ));

    return id(new AphrontAjaxResponse())
      ->setContent($content);
  }

  private function renderCommentForm(PhorgeFile $file) {
    $viewer = $this->getViewer();

    if (!$viewer->isLoggedIn()) {
      $login_href = id(new PhutilURI('/auth/start/'))
        ->replaceQueryParam('next', '/'.$file->getMonogram());
      return id(new PHUIFormLayoutView())
        ->addClass('phui-comment-panel-empty')
        ->appendChild(
          id(new PHUIButtonView())
          ->setTag('a')
          ->setText(pht('Log In to Comment'))
          ->setHref((string)$login_href));
    }

    $draft = PhorgeDraft::newFromUserAndKey(
      $viewer,
      $file->getPHID());
    $post_uri = $this->getApplicationURI('thread/'.$file->getPHID().'/');

    $form = id(new AphrontFormView())
      ->setUser($viewer)
      ->setAction($post_uri)
      ->addSigil('lightbox-comment-form')
      ->addClass('lightbox-comment-form')
      ->setWorkflow(true)
      ->appendChild(
        id(new PhorgeRemarkupControl())
        ->setUser($viewer)
        ->setName('comment')
        ->setValue($draft->getDraft()))
      ->appendChild(
        id(new AphrontFormSubmitControl())
          ->setValue(pht('Comment')));

    $view = phutil_tag_div('phui-comment-panel', $form);

    return $view;

  }

}
