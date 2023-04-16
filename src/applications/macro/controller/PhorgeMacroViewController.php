<?php

final class PhorgeMacroViewController
  extends PhorgeMacroController {

  public function shouldAllowPublic() {
    return true;
  }

  public function handleRequest(AphrontRequest $request) {
    $viewer = $request->getViewer();
    $id = $request->getURIData('id');

    $macro = id(new PhorgeMacroQuery())
      ->setViewer($viewer)
      ->withIDs(array($id))
      ->needFiles(true)
      ->executeOne();
    if (!$macro) {
      return new Aphront404Response();
    }

    $title_short = pht('Macro "%s"', $macro->getName());
    $title_long  = pht('Image Macro "%s"', $macro->getName());

    $curtain = $this->buildCurtain($macro);
    $subheader = $this->buildSubheaderView($macro);
    $file = $this->buildFileView($macro);
    $details = $this->buildPropertySectionView($macro);

    $crumbs = $this->buildApplicationCrumbs();
    $crumbs->addTextCrumb($macro->getName());
    $crumbs->setBorder(true);

    $timeline = $this->buildTransactionTimeline(
      $macro,
      new PhorgeMacroTransactionQuery());

    $comment_form = $this->buildCommentForm($macro, $timeline);

    $header = id(new PHUIHeaderView())
      ->setUser($viewer)
      ->setPolicyObject($macro)
      ->setHeader($macro->getName())
      ->setHeaderIcon('fa-file-image-o');

    if (!$macro->getIsDisabled()) {
      $header->setStatus('fa-check', 'bluegrey', pht('Active'));
    } else {
      $header->setStatus('fa-ban', 'indigo', pht('Archived'));
    }

    $view = id(new PHUITwoColumnView())
      ->setHeader($header)
      ->setSubheader($subheader)
      ->setCurtain($curtain)
      ->setMainColumn(array(
        $timeline,
        $comment_form,
      ))
      ->addPropertySection(pht('Macro'), $file)
      ->addPropertySection(pht('Details'), $details);

    return $this->newPage()
      ->setTitle($title_short)
      ->setCrumbs($crumbs)
      ->setPageObjectPHIDs(array($macro->getPHID()))
      ->appendChild($view);
  }

  private function buildCommentForm(
    PhorgeFileImageMacro $macro, $timeline) {
    $viewer = $this->getViewer();

    return id(new PhorgeMacroEditEngine())
      ->setViewer($viewer)
      ->buildEditEngineCommentView($macro)
      ->setTransactionTimeline($timeline);
  }

  private function buildCurtain(
    PhorgeFileImageMacro $macro) {
    $can_manage = $this->hasApplicationCapability(
      PhorgeMacroManageCapability::CAPABILITY);

    $curtain = $this->newCurtainView($macro);

    $curtain->addAction(
        id(new PhorgeActionView())
        ->setName(pht('Edit Macro'))
        ->setHref($this->getApplicationURI('/edit/'.$macro->getID().'/'))
        ->setDisabled(!$can_manage)
        ->setWorkflow(!$can_manage)
        ->setIcon('fa-pencil'));

    $curtain->addAction(
      id(new PhorgeActionView())
        ->setName(pht('Edit Audio'))
        ->setHref($this->getApplicationURI('/audio/'.$macro->getID().'/'))
        ->setDisabled(!$can_manage)
        ->setWorkflow(!$can_manage)
        ->setIcon('fa-music'));

    if ($macro->getIsDisabled()) {
      $curtain->addAction(
        id(new PhorgeActionView())
          ->setName(pht('Activate Macro'))
          ->setHref($this->getApplicationURI('/disable/'.$macro->getID().'/'))
          ->setWorkflow(true)
          ->setDisabled(!$can_manage)
          ->setIcon('fa-check'));
    } else {
      $curtain->addAction(
        id(new PhorgeActionView())
          ->setName(pht('Archive Macro'))
          ->setHref($this->getApplicationURI('/disable/'.$macro->getID().'/'))
          ->setWorkflow(true)
          ->setDisabled(!$can_manage)
          ->setIcon('fa-ban'));
    }

    return $curtain;
  }

  private function buildSubheaderView(
    PhorgeFileImageMacro $macro) {
    $viewer = $this->getViewer();

    $author_phid = $macro->getAuthorPHID();

    $author = $viewer->renderHandle($author_phid)->render();
    $date = phorge_datetime($macro->getDateCreated(), $viewer);
    $author = phutil_tag('strong', array(), $author);

    $handles = $viewer->loadHandles(array($author_phid));
    $image_uri = $handles[$author_phid]->getImageURI();
    $image_href = $handles[$author_phid]->getURI();

    if (!$date) {
      $content = pht(
        'Masterfully imagined by %s in ages long past.', $author);
    } else {
      $content = pht('Masterfully imagined by %s on %s.', $author, $date);
    }

    return id(new PHUIHeadThingView())
      ->setImage($image_uri)
      ->setImageHref($image_href)
      ->setContent($content);
  }

  private function buildPropertySectionView(
    PhorgeFileImageMacro $macro) {
    $viewer = $this->getViewer();

    $view = id(new PHUIPropertyListView())
      ->setUser($viewer);

    switch ($macro->getAudioBehavior()) {
      case PhorgeFileImageMacro::AUDIO_BEHAVIOR_ONCE:
        $view->addProperty(pht('Audio Behavior'), pht('Play Once'));
        break;
      case PhorgeFileImageMacro::AUDIO_BEHAVIOR_LOOP:
        $view->addProperty(pht('Audio Behavior'), pht('Loop'));
        break;
    }

    $audio_phid = $macro->getAudioPHID();
    if ($audio_phid) {
      $view->addProperty(
        pht('Audio'),
        $viewer->renderHandle($audio_phid));
    }

    if ($view->hasAnyProperties()) {
      return $view;
    }

    return null;
  }

  private function buildFileView(
    PhorgeFileImageMacro $macro) {
    $viewer = $this->getViewer();

    $view = id(new PHUIPropertyListView())
      ->setUser($viewer);

    $file = $macro->getFile();
    if ($file) {
      $view->addImageContent(
        phutil_tag(
          'img',
          array(
            'src'     => $file->getViewURI(),
            'class'   => 'phorge-image-macro-hero',
          )));
      return $view;
    }
    return null;
  }

}
