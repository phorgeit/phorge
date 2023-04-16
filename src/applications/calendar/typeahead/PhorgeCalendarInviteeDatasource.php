<?php

final class PhorgeCalendarInviteeDatasource
  extends PhorgeTypeaheadCompositeDatasource {

  public function getBrowseTitle() {
    return pht('Browse Invitees');
  }

  public function getPlaceholderText() {
    return pht('Type a user or project name, or function...');
  }

  public function getDatasourceApplicationClass() {
    return 'PhorgeCalendarApplication';
  }

  public function getComponentDatasources() {
    return array(
      new PhorgeCalendarInviteeUserDatasource(),
      new PhorgeCalendarInviteeViewerFunctionDatasource(),
      new DifferentialExactUserFunctionDatasource(),
      new PhorgeProjectDatasource(),
    );
  }

  public static function expandInvitees(
    PhorgeUser $viewer,
    array $values) {

    $phids = array();
    foreach ($values as $value) {
      if (phid_get_type($value) == PhorgePeopleUserPHIDType::TYPECONST) {
        $phids[] = $value;
      }
    }

    if (!$phids) {
      return $values;
    }

    $projects = id(new PhorgeProjectQuery())
       ->setViewer($viewer)
       ->withMemberPHIDs($phids)
       ->execute();
    foreach ($projects as $project) {
      $values[] = $project->getPHID();
    }

    return $values;
  }

}
