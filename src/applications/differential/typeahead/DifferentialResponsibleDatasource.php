<?php

final class DifferentialResponsibleDatasource
  extends PhorgeTypeaheadCompositeDatasource {

  public function getBrowseTitle() {
    return pht('Browse Responsible Users');
  }

  public function getPlaceholderText() {
    return pht('Type a user, project, or package name, or function...');
  }

  public function getDatasourceApplicationClass() {
    return 'PhorgeDifferentialApplication';
  }

  public function getComponentDatasources() {
    return array(
      new DifferentialResponsibleUserDatasource(),
      new DifferentialResponsibleViewerFunctionDatasource(),
      new DifferentialExactUserFunctionDatasource(),
      new PhorgeProjectDatasource(),
      new PhorgeOwnersPackageDatasource(),
    );
  }

  public static function expandResponsibleUsers(
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
      $phids[] = $project->getPHID();
      $values[] = $project->getPHID();
    }

    $packages = id(new PhorgeOwnersPackageQuery())
      ->setViewer($viewer)
      ->withOwnerPHIDs($phids)
      ->execute();
    foreach ($packages as $package) {
      $values[] = $package->getPHID();
    }

    return $values;
  }

}
