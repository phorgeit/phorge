<?php

final class PhorgeOwnersOwner extends PhorgeOwnersDAO {

  protected $packageID;

  // this can be a project or a user. We assume that all members of a project
  // owner also own the package; use the loadAffiliatedUserPHIDs method if
  // you want to recursively grab all user ids that own a package
  protected $userPHID;

  protected function getConfiguration() {
    return array(
      self::CONFIG_TIMESTAMPS => false,
      self::CONFIG_KEY_SCHEMA => array(
        'packageID' => array(
          'columns' => array('packageID', 'userPHID'),
          'unique' => true,
        ),
        'userPHID' => array(
          'columns' => array('userPHID'),
        ),
      ),
    ) + parent::getConfiguration();
  }

  public static function loadAllForPackages(array $packages) {
    assert_instances_of($packages, 'PhorgeOwnersPackage');
    if (!$packages) {
      return array();
    }
    return id(new PhorgeOwnersOwner())->loadAllWhere(
      'packageID IN (%Ls)',
      mpull($packages, 'getID'));
  }

  // Loads all user phids affiliated with a set of packages. This includes both
  // user owners and all members of any project owners
  public static function loadAffiliatedUserPHIDs(array $package_ids) {
    if (!$package_ids) {
      return array();
    }

    $owners = id(new PhorgeOwnersOwner())->loadAllWhere(
      'packageID IN (%Ls)',
      $package_ids);

    $type_user = PhorgePeopleUserPHIDType::TYPECONST;
    $type_project = PhorgeProjectProjectPHIDType::TYPECONST;

    $user_phids = array();
    $project_phids = array();
    foreach ($owners as $owner) {
      $owner_phid = $owner->getUserPHID();
      switch (phid_get_type($owner_phid)) {
        case PhorgePeopleUserPHIDType::TYPECONST:
          $user_phids[] = $owner_phid;
          break;
        case PhorgeProjectProjectPHIDType::TYPECONST:
          $project_phids[] = $owner_phid;
          break;
      }
    }

    if ($project_phids) {
      $projects = id(new PhorgeProjectQuery())
        ->setViewer(PhorgeUser::getOmnipotentUser())
        ->withPHIDs($project_phids)
        ->needMembers(true)
        ->execute();
      foreach ($projects as $project) {
        foreach ($project->getMemberPHIDs() as $member_phid) {
          $user_phids[] = $member_phid;
        }
      }
    }

    $user_phids = array_fuse($user_phids);
    return array_values($user_phids);
  }
}
