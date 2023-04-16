<?php

final class PhorgeProjectViewController
  extends PhorgeProjectController {

  public function shouldAllowPublic() {
    return true;
  }

  public function handleRequest(AphrontRequest $request) {
    $request = $this->getRequest();
    $viewer = $request->getViewer();

    $response = $this->loadProject();
    if ($response) {
      return $response;
    }
    $project = $this->getProject();

    $engine = $this->getProfileMenuEngine();
    $default = $engine->getDefaultMenuItemConfiguration();

    // If defaults are broken somehow, serve the manage page. See T13033 for
    // discussion.
    if ($default) {
      $default_key = $default->getBuiltinKey();
    } else {
      $default_key = PhorgeProject::ITEM_MANAGE;
    }

    switch ($default_key) {
      case PhorgeProject::ITEM_WORKBOARD:
        $controller_object = new PhorgeProjectBoardViewController();
        break;
      case PhorgeProject::ITEM_PROFILE:
        $controller_object = new PhorgeProjectProfileController();
        break;
      case PhorgeProject::ITEM_MANAGE:
        $controller_object = new PhorgeProjectManageController();
        break;
      default:
        return $engine->buildResponse();
    }

    return $this->delegateToController($controller_object);
  }

}
