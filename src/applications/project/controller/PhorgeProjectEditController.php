<?php

final class PhorgeProjectEditController
  extends PhorgeProjectController {

  private $engine;

  public function setEngine(PhorgeProjectEditEngine $engine) {
    $this->engine = $engine;
    return $this;
  }

  public function getEngine() {
    return $this->engine;
  }

  public function handleRequest(AphrontRequest $request) {
    $viewer = $this->getViewer();

    $engine = id(new PhorgeProjectEditEngine())
      ->setController($this);

    $this->setEngine($engine);

    $id = $request->getURIData('id');
    if (!$id) {
      // This capability is checked again later, but checking it here
      // explicitly gives us a better error message.
      $this->requireApplicationCapability(
        ProjectCreateProjectsCapability::CAPABILITY);

      $parent_id = head($request->getArr('parent'));
      if (!$parent_id) {
        $parent_id = $request->getStr('parent');
      }

      if ($parent_id) {
        $is_milestone = false;
      } else {
        $parent_id = head($request->getArr('milestone'));
        if (!$parent_id) {
          $parent_id = $request->getStr('milestone');
        }
        $is_milestone = true;
      }

      if ($parent_id) {
        $query = id(new PhorgeProjectQuery())
          ->setViewer($viewer)
          ->needImages(true)
          ->requireCapabilities(
            array(
              PhorgePolicyCapability::CAN_VIEW,
              PhorgePolicyCapability::CAN_EDIT,
            ));

        if (ctype_digit($parent_id)) {
          $query->withIDs(array($parent_id));
        } else {
          $query->withPHIDs(array($parent_id));
        }

        $parent = $query->executeOne();

        if ($is_milestone) {
          if (!$parent->supportsMilestones()) {
            $cancel_uri = "/project/subprojects/{$parent_id}/";
            return $this->newDialog()
              ->setTitle(pht('No Milestones'))
              ->appendParagraph(
                pht('You can not add milestones to this project.'))
              ->addCancelButton($cancel_uri);
          }
          $engine->setMilestoneProject($parent);
        } else {
          if (!$parent->supportsSubprojects()) {
            $cancel_uri = "/project/subprojects/{$parent_id}/";
            return $this->newDialog()
              ->setTitle(pht('No Subprojects'))
              ->appendParagraph(
                pht('You can not add subprojects to this project.'))
              ->addCancelButton($cancel_uri);
          }
          $engine->setParentProject($parent);
        }

        $this->setProject($parent);
      }
    }

    return $engine->buildResponse();
  }

  protected function buildApplicationCrumbs() {
    $crumbs = parent::buildApplicationCrumbs();

    $engine = $this->getEngine();
    if ($engine) {
      $parent = $engine->getParentProject();
      $milestone = $engine->getMilestoneProject();
      if ($parent || $milestone) {
        $id = nonempty($parent, $milestone)->getID();
        $crumbs->addTextCrumb(
          pht('Subprojects'),
          $this->getApplicationURI("subprojects/{$id}/"));
      }
    }

    return $crumbs;
  }

}
