<?php

final class PhabricatorSlowvotePollController
  extends PhabricatorSlowvoteController {

  public function shouldAllowPublic() {
    return true;
  }

  public function handleRequest(AphrontRequest $request) {
    $viewer = $request->getViewer();
    $id = $request->getURIData('id');

    $poll = id(new PhabricatorSlowvoteQuery())
      ->setViewer($viewer)
      ->withIDs(array($id))
      ->needOptions(true)
      ->needChoices(true)
      ->needViewerChoices(true)
      ->executeOne();
    if (!$poll) {
      return new Aphront404Response();
    }

    $poll_view = id(new SlowvoteEmbedView())
      ->setViewer($viewer)
      ->setPoll($poll);

    if ($request->isAjax()) {
      return id(new AphrontAjaxResponse())
        ->setContent(
          array(
            'pollID' => $poll->getID(),
            'contentHTML' => $poll_view->render(),
          ));
    }

    $status = $poll->getStatusObject();

    $header_icon = $status->getHeaderTagIcon();
    $header_color = $status->getHeaderTagColor();
    $header_name = $status->getName();

    $header = id(new PHUIHeaderView())
      ->setHeader($poll->getQuestion())
      ->setViewer($viewer)
      ->setStatus($header_icon, $header_color, $header_name)
      ->setPolicyObject($poll)
      ->setHeaderIcon('fa-bar-chart');

    $curtain = $this->buildCurtain($poll);
    $subheader = $this->buildSubheaderView($poll);

    $crumbs = $this->buildApplicationCrumbs();
    $crumbs->addTextCrumb($poll->getMonogram());
    $crumbs->setBorder(true);

    $timeline = $this->buildTransactionTimeline(
      $poll,
      new PhabricatorSlowvoteTransactionQuery());
    $add_comment = $this->buildCommentForm($poll);

    $poll_content = array(
      $poll_view,
      $timeline,
      $add_comment,
    );

    $view = id(new PHUITwoColumnView())
      ->setHeader($header)
      ->setSubheader($subheader)
      ->setCurtain($curtain)
      ->setMainColumn($poll_content);

    return $this->newPage()
      ->setTitle(
        pht(
          '%s %s',
          $poll->getMonogram(),
          $poll->getQuestion()))
      ->setCrumbs($crumbs)
      ->setPageObjectPHIDs(array($poll->getPHID()))
      ->appendChild($view);
  }

  private function buildCurtain(PhabricatorSlowvotePoll $poll) {
    $viewer = $this->getViewer();

    $can_edit = PhabricatorPolicyFilter::hasCapability(
      $viewer,
      $poll,
      PhabricatorPolicyCapability::CAN_EDIT);

    $curtain = $this->newCurtainView($poll);

    $is_closed = $poll->isClosed();
    $close_poll_text = $is_closed ? pht('Reopen Poll') : pht('Close Poll');
    $close_poll_icon = $is_closed ? 'fa-check' : 'fa-ban';

    $curtain->addAction(
      id(new PhabricatorActionView())
        ->setName(pht('Edit Poll'))
        ->setIcon('fa-pencil')
        ->setHref($this->getApplicationURI('edit/'.$poll->getID().'/'))
        ->setDisabled(!$can_edit)
        ->setWorkflow(!$can_edit));

    $curtain->addAction(
      id(new PhabricatorActionView())
        ->setName($close_poll_text)
        ->setIcon($close_poll_icon)
        ->setHref($this->getApplicationURI('close/'.$poll->getID().'/'))
        ->setDisabled(!$can_edit)
        ->setWorkflow(true));

    return $curtain;
  }

  private function buildSubheaderView(
    PhabricatorSlowvotePoll $poll) {
    $viewer = $this->getViewer();

    $author = $viewer->renderHandle($poll->getAuthorPHID())->render();
    $date = vixon_datetime($poll->getDateCreated(), $viewer);
    $author = phutil_tag('strong', array(), $author);

    $person = id(new PhabricatorPeopleQuery())
      ->setViewer($viewer)
      ->withPHIDs(array($poll->getAuthorPHID()))
      ->needProfileImage(true)
      ->executeOne();

    $image_uri = $person->getProfileImageURI();
    $image_href = '/p/'.$person->getUsername();

    $content = pht('Asked by %s on %s.', $author, $date);

    return id(new PHUIHeadThingView())
      ->setImage($image_uri)
      ->setImageHref($image_href)
      ->setContent($content);
  }

  private function buildCommentForm(PhabricatorSlowvotePoll $poll) {
    $viewer = $this->getRequest()->getUser();

    $is_serious = PhabricatorEnv::getEnvConfig('phabricator.serious-business');

    $add_comment_header = $is_serious
      ? pht('Add Comment')
      : pht('Enter Deliberations');

    $draft = PhabricatorDraft::newFromUserAndKey($viewer, $poll->getPHID());

    return id(new PhabricatorApplicationTransactionCommentView())
      ->setViewer($viewer)
      ->setObject($poll)
      ->setDraft($draft)
      ->setHeaderText($add_comment_header)
      ->setAction($this->getApplicationURI('/comment/'.$poll->getID().'/'))
      ->setSubmitButtonName(pht('Add Comment'));
  }

}
