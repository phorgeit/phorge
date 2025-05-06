<?php

final class PhabricatorPeopleProfileTasksAuthoredController
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

    $class = 'PhabricatorManiphestApplication';
    if (!PhabricatorApplication::isClassInstalledForViewer($class, $viewer)) {
      return new Aphront404Response();
    }

    $this->setUser($user);
    $title = array(pht('Authored Tasks'), $user->getUsername());
    $header = $this->buildProfileHeader();
    $tasks = $this->buildTasksView($user);

    $crumbs = $this->buildApplicationCrumbs();
    $crumbs->addTextCrumb(pht('Authored Tasks'));
    $crumbs->setBorder(true);

    $nav = $this->newNavigation(
      $user,
      PhabricatorPeopleProfileMenuEngine::ITEM_TASKS_AUTHORED);

    $view = id(new PHUITwoColumnView())
      ->setHeader($header)
      ->addClass('project-view-home')
      ->addClass('project-view-people-home')
      ->setFooter(array(
        $tasks,
      ));

    return $this->newPage()
      ->setTitle($title)
      ->setCrumbs($crumbs)
      ->setNavigation($nav)
      ->appendChild($view);
  }

  private function buildTasksView(PhabricatorUser $user) {
    $viewer = $this->getViewer();

    $open = ManiphestTaskStatus::getOpenStatusConstants();

    $tasks = id(new ManiphestTaskQuery())
      ->setViewer($viewer)
      ->withAuthors(array($user->getPHID()))
      ->needProjectPHIDs(true)
      ->setLimit(100)
      ->execute();

    $handles = ManiphestTaskListView::loadTaskHandles($viewer, $tasks);

    $list = id(new ManiphestTaskListView())
      ->setUser($viewer)
      ->setHandles($handles)
      ->setTasks($tasks)
      ->setNoDataString(pht('No authored tasks.'));

    $view = id(new PHUIObjectBoxView())
      ->setHeaderText(pht('Authored Tasks'))
      ->setBackground(PHUIObjectBoxView::BLUE_PROPERTY)
      ->appendChild($list);

    return $view;
  }
}
