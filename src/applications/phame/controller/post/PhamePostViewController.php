<?php

final class PhamePostViewController
  extends PhameLiveController {

  public function handleRequest(AphrontRequest $request) {
    $response = $this->setupLiveEnvironment();
    if ($response) {
      return $response;
    }

    $viewer = $request->getViewer();
    $moved = $request->getStr('moved');

    $post = $this->getPost();
    $blog = $this->getBlog();

    $is_live = $this->getIsLive();
    $is_external = $this->getIsExternal();

    $header = id(new PHUIHeaderView())
      ->addClass('phame-header-bar')
      ->setUser($viewer);

    $hero = $this->buildPhamePostHeader($post);

    if (!$is_external && $viewer->isLoggedIn()) {
      $actions = $this->renderActions($post);
      $header->setPolicyObject($post);
      $header->setActionList($actions);
    }

    $document = id(new PHUIDocumentView())
      ->setHeader($header);

    if ($moved) {
      $document->appendChild(
        id(new PHUIInfoView())
          ->setSeverity(PHUIInfoView::SEVERITY_NOTICE)
          ->appendChild(pht('Post moved successfully.')));
    }

    if ($post->isDraft()) {
      $document->appendChild(
        id(new PHUIInfoView())
          ->setSeverity(PHUIInfoView::SEVERITY_NOTICE)
          ->setTitle(pht('Draft Post'))
          ->appendChild(
            pht(
              'This is a draft, and is only visible to you and other users '.
              'who can edit %s. Use "Publish" to publish this post.',
              $viewer->renderHandle($post->getBlogPHID()))));
    }

    if ($post->isArchived()) {
      $document->appendChild(
        id(new PHUIInfoView())
          ->setSeverity(PHUIInfoView::SEVERITY_ERROR)
          ->setTitle(pht('Archived Post'))
          ->appendChild(
            pht(
              'This post has been archived, and is only visible to you and '.
              'other users who can edit %s.',
              $viewer->renderHandle($post->getBlogPHID()))));
    }

    if (!$post->getBlog()) {
      $document->appendChild(
        id(new PHUIInfoView())
          ->setSeverity(PHUIInfoView::SEVERITY_WARNING)
          ->setTitle(pht('Not On A Blog'))
          ->appendChild(
            pht('This post is not associated with a blog (the blog may have '.
                'been deleted). Use "Move Post" to move it to a new blog.')));
    }

    $engine = id(new PhabricatorMarkupEngine())
      ->setViewer($viewer)
      ->addObject($post, PhamePost::MARKUP_FIELD_BODY)
      ->process();

    $document->appendChild(
      phutil_tag(
         'div',
        array(
          'class' => 'phabricator-remarkup',
        ),
        $engine->getOutput($post, PhamePost::MARKUP_FIELD_BODY)));

    $blogger = id(new PhabricatorPeopleQuery())
      ->setViewer($viewer)
      ->withPHIDs(array($post->getBloggerPHID()))
      ->needProfileImage(true)
      ->executeOne();
    $blogger_profile = $blogger->loadUserProfile();


    $author_uri = '/p/'.$blogger->getUsername().'/';
    $author_uri = PhabricatorEnv::getURI($author_uri);

    $author = phutil_tag(
      'a',
      array(
        'href' => $author_uri,
      ),
      $blogger->getUsername());

    $date = vixon_datetime($post->getDatePublished(), $viewer);
    if ($post->isDraft()) {
      $subtitle = pht('Unpublished draft by %s.', $author);
    } else if ($post->isArchived()) {
      $subtitle = pht('Archived post by %s.', $author);
    } else {
      $subtitle = pht('Written by %s on %s.', $author, $date);
    }

    $user_icon = $blogger_profile->getIcon();
    $user_icon = PhabricatorPeopleIconSet::getIconIcon($user_icon);
    $user_icon = id(new PHUIIconView())->setIcon($user_icon);

    $about = id(new PhameDescriptionView())
      ->setTitle($subtitle)
      ->setDescription(
        array(
          $user_icon,
          ' ',
          $blogger_profile->getDisplayTitle(),
        ))
      ->setImage($blogger->getProfileImageURI())
      ->setImageHref($author_uri);

    $monogram = $post->getMonogram();
    $timeline = $this->buildTransactionTimeline(
      $post,
      id(new PhamePostTransactionQuery())
      ->withTransactionTypes(array(PhabricatorTransactions::TYPE_COMMENT)));
    $timeline->setQuoteRef($monogram);

    if ($is_external || !$viewer->isLoggedIn()) {
      $add_comment = null;
    } else {
      $add_comment = $this->buildCommentForm($post, $timeline);
      $add_comment = phutil_tag_div('mlb mlt phame-comment-view', $add_comment);
    }

    $timeline = phutil_tag_div('phui-document-view-pro-box', $timeline);

    list($prev, $next) = $this->loadAdjacentPosts($post);

    $properties = id(new PHUIPropertyListView())
      ->setUser($viewer)
      ->setObject($post);

    $is_live = $this->getIsLive();
    $is_external = $this->getIsExternal();
    $next_view = new PhameNextPostView();
    if ($next) {
      $next_view->setNext($next->getTitle(),
        $next->getBestURI($is_live, $is_external));
    }
    if ($prev) {
      $next_view->setPrevious($prev->getTitle(),
        $prev->getBestURI($is_live, $is_external));
    }

    $monogram = $post->getMonogram();

    $document->setFoot($next_view);
    $crumbs = $this->buildApplicationCrumbs();
    $properties = phutil_tag_div('phui-document-view-pro-box', $properties);

    // Public viewers like search engines will not see the monogram
    $title = $viewer->isLoggedIn()
      ? pht('%s %s', $monogram, $post->getTitle())
      : $post->getTitle();

    $page = $this->newPage()
      ->setTitle($title)
      ->setPageObjectPHIDs(array($post->getPHID()))
      ->setCrumbs($crumbs)
      ->appendChild(
        array(
          $hero,
          $document,
          $about,
          $properties,
          $timeline,
          $add_comment,
      ));

    if ($is_live) {
      $page
        ->setShowChrome(false)
        ->setShowFooter(false);
    }

    return $page;
  }

  private function renderActions(PhamePost $post) {
    $viewer = $this->getViewer();

    $actions = id(new PhabricatorActionListView())
      ->setObject($post)
      ->setUser($viewer);

    $can_edit = PhabricatorPolicyFilter::hasCapability(
      $viewer,
      $post,
      PhabricatorPolicyCapability::CAN_EDIT);

    $id = $post->getID();

    $actions->addAction(
      id(new PhabricatorActionView())
        ->setIcon('fa-pencil')
        ->setHref($this->getApplicationURI('post/edit/'.$id.'/'))
        ->setName(pht('Edit Post'))
        ->setDisabled(!$can_edit));

    $actions->addAction(
      id(new PhabricatorActionView())
        ->setIcon('fa-camera-retro')
        ->setHref($this->getApplicationURI('post/header/'.$id.'/'))
        ->setName(pht('Edit Header Image'))
        ->setDisabled(!$can_edit));

    $actions->addAction(
      id(new PhabricatorActionView())
        ->setIcon('fa-arrows')
        ->setHref($this->getApplicationURI('post/move/'.$id.'/'))
        ->setName(pht('Move Post'))
        ->setDisabled(!$can_edit)
        ->setWorkflow(true));

    $actions->addAction(
      id(new PhabricatorActionView())
        ->setIcon('fa-history')
        ->setHref($this->getApplicationURI('post/history/'.$id.'/'))
        ->setName(pht('View History')));

    if ($post->isDraft()) {
      $actions->addAction(
        id(new PhabricatorActionView())
          ->setIcon('fa-eye')
          ->setHref($this->getApplicationURI('post/publish/'.$id.'/'))
          ->setName(pht('Publish'))
          ->setDisabled(!$can_edit)
          ->setWorkflow(true));
      $actions->addAction(
        id(new PhabricatorActionView())
          ->setIcon('fa-ban')
          ->setHref($this->getApplicationURI('post/archive/'.$id.'/'))
          ->setName(pht('Archive'))
          ->setDisabled(!$can_edit)
          ->setWorkflow(true));
    } else if ($post->isArchived()) {
      $actions->addAction(
        id(new PhabricatorActionView())
          ->setIcon('fa-eye')
          ->setHref($this->getApplicationURI('post/publish/'.$id.'/'))
          ->setName(pht('Publish'))
          ->setDisabled(!$can_edit)
          ->setWorkflow(true));
    } else {
      $actions->addAction(
        id(new PhabricatorActionView())
          ->setIcon('fa-eye-slash')
          ->setHref($this->getApplicationURI('post/unpublish/'.$id.'/'))
          ->setName(pht('Unpublish'))
          ->setDisabled(!$can_edit)
          ->setWorkflow(true));
      $actions->addAction(
        id(new PhabricatorActionView())
          ->setIcon('fa-ban')
          ->setHref($this->getApplicationURI('post/archive/'.$id.'/'))
          ->setName(pht('Archive'))
          ->setDisabled(!$can_edit)
          ->setWorkflow(true));
    }

    if ($post->isDraft()) {
      $live_name = pht('Preview');
    } else {
      $live_name = pht('View Live');
    }

    if (!$post->isArchived()) {
      $actions->addAction(
        id(new PhabricatorActionView())
          ->setUser($viewer)
          ->setIcon('fa-globe')
          ->setHref($post->getLiveURI())
          ->setName($live_name));
    }

    return $actions;
  }

  private function buildCommentForm(PhamePost $post, $timeline) {
    $viewer = $this->getViewer();

    $box = id(new PhamePostEditEngine())
      ->setViewer($viewer)
      ->buildEditEngineCommentView($post)
      ->setTransactionTimeline($timeline);

    return phutil_tag_div('phui-document-view-pro-box', $box);
  }

  private function loadAdjacentPosts(PhamePost $post) {
    $viewer = $this->getViewer();

    $pager = id(new AphrontCursorPagerView())
      ->setPageSize(1);

    $prev_pager = id(clone $pager)
      ->setAfterID($post->getID());

    $next_pager = id(clone $pager)
      ->setBeforeID($post->getID());

    $query = id(new PhamePostQuery())
      ->setViewer($viewer)
      ->withVisibility(array(PhameConstants::VISIBILITY_PUBLISHED))
      ->withBlogPHIDs(array($post->getBlog()->getPHID()))
      ->setLimit(1);

    $prev = id(clone $query)
      ->executeWithCursorPager($prev_pager);

    $next = id(clone $query)
      ->executeWithCursorPager($next_pager);

    return array(head($prev), head($next));
  }

  private function buildPhamePostHeader(
    PhamePost $post) {

    $image = null;
    if ($post->getHeaderImagePHID()) {
      $image = phutil_tag(
        'div',
        array(
          'class' => 'phame-header-hero',
        ),
        phutil_tag(
          'img',
          array(
            'src'     => $post->getHeaderImageURI(),
            'class'   => 'phame-header-image',
          )));
    }

    $title = phutil_tag_div('phame-header-title', $post->getTitle());
    $subtitle = null;
    if ($post->getSubtitle()) {
      $subtitle = phutil_tag_div('phame-header-subtitle', $post->getSubtitle());
    }

    return phutil_tag_div(
      'phame-mega-header', array($image, $title, $subtitle));

  }

}
