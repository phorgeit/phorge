<?php

final class PhabricatorPeopleProfileRevisionsController
  extends PhabricatorPeopleProfileController {

  public function handleRequest(AphrontRequest $request) {
    $viewer = $this->getViewer();
    $id = $request->getURIData('id');

    $user = id(new PhabricatorPeopleQuery())
      ->setViewer($viewer)
      ->withIDs(array($id))
      ->needProfile(true)
      ->needProfileImage(true)
      ->needAvailability(true)
      ->executeOne();
    if (!$user) {
      return new Aphront404Response();
    }

    $class = PhabricatorDifferentialApplication::class;
    if (!PhabricatorApplication::isClassInstalledForViewer($class, $viewer)) {
      return new Aphront404Response();
    }

    $this->setUser($user);
    $title = array(pht('Recent Revisions'), $user->getUsername());
    $header = $this->buildProfileHeader();
    $commits = $this->buildRevisionsView($user);

    $crumbs = $this->buildApplicationCrumbs();
    $crumbs->addTextCrumb(pht('Recent Revisions'));
    $crumbs->setBorder(true);

    $nav = $this->newNavigation(
      $user,
      PhabricatorPeopleProfileMenuEngine::ITEM_REVISIONS);

    $view = id(new PHUITwoColumnView())
      ->setHeader($header)
      ->addClass('project-view-home')
      ->addClass('project-view-people-home')
      ->setFooter(array(
        $commits,
      ));

    return $this->newPage()
      ->setTitle($title)
      ->setCrumbs($crumbs)
      ->setNavigation($nav)
      ->appendChild($view);
  }

  private function buildRevisionsView(PhabricatorUser $user) {
    $viewer = $this->getViewer();


    $engine = id(new DifferentialRevisionSearchEngine())
      ->setViewer($viewer)
      ->setNoDataString(pht('No recent revisions.'));

    $query = $engine->newQuery()
      ->withAuthors(array($user->getPHID()))
      ->needDrafts(true)
      ->needReviewers(true)
      ->setLimit(100);

    $results = $engine->executeQueryAndRender($query);

    // The thing that's returned from `engine->renderResults` is a
    // `PhabricatorApplicationSearchResultView`, which //isn't// a View and
    // cannot be rendered directly.
    // In //this case//, I know the result is in in `getContent()`.
    $list = $results->getContent();

    return id(new PHUIObjectBoxView())
      ->setHeaderText(pht('Recent Revisions'))
      ->setBackground(PHUIObjectBoxView::BLUE_PROPERTY)
      ->appendChild($list);
  }

}
