<?php

final class PhorgeProjectWatcherListView
  extends PhorgeProjectUserListView {

  protected function canEditList() {
    $viewer = $this->getViewer();
    $project = $this->getProject();

    return PhorgePolicyFilter::hasCapability(
      $viewer,
      $project,
      PhorgePolicyCapability::CAN_EDIT);
  }

  protected function getNoDataString() {
    return pht('This project does not have any watchers.');
  }

  protected function getRemoveURI($phid) {
    $project = $this->getProject();
    $id = $project->getID();
    return "/project/watchers/{$id}/remove/?phid={$phid}";
  }

  protected function getHeaderText() {
    return pht('Watchers');
  }

  protected function getMembershipNote() {
    $viewer = $this->getViewer();
    $viewer_phid = $viewer->getPHID();
    $project = $this->getProject();

    $note = null;
    if ($project->isUserWatcher($viewer_phid)) {
      $note = pht('You are watching this project and will receive mail about '.
                  'changes made to any related object.');
    }
    return $note;
  }

}
