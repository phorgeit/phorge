<?php

final class PhorgeMetaMTAActorQuery extends PhorgeQuery {

  private $phids = array();
  private $viewer;

  public function setViewer(PhorgeUser $viewer) {
    $this->viewer = $viewer;
    return $this;
  }

  public function getViewer() {
    return $this->viewer;
  }

  public function withPHIDs(array $phids) {
    $this->phids = $phids;
    return $this;
  }

  public function execute() {
    $phids = array_fuse($this->phids);
    $actors = array();
    $type_map = array();
    foreach ($phids as $phid) {
      $type_map[phid_get_type($phid)][] = $phid;
      $actors[$phid] = id(new PhorgeMetaMTAActor())->setPHID($phid);
    }

    // TODO: Move this to PhorgePHIDType, or the objects, or some
    // interface.

    foreach ($type_map as $type => $phids) {
      switch ($type) {
        case PhorgePeopleUserPHIDType::TYPECONST:
          $this->loadUserActors($actors, $phids);
          break;
        default:
          $this->loadUnknownActors($actors, $phids);
          break;
      }
    }

    return $actors;
  }

  private function loadUserActors(array $actors, array $phids) {
    assert_instances_of($actors, 'PhorgeMetaMTAActor');

    $emails = id(new PhorgeUserEmail())->loadAllWhere(
      'userPHID IN (%Ls) AND isPrimary = 1',
      $phids);
    $emails = mpull($emails, null, 'getUserPHID');

    $users = id(new PhorgePeopleQuery())
      ->setViewer($this->getViewer())
      ->withPHIDs($phids)
      ->needUserSettings(true)
      ->execute();
    $users = mpull($users, null, 'getPHID');

    foreach ($phids as $phid) {
      $actor = $actors[$phid];

      $user = idx($users, $phid);
      if (!$user) {
        $actor->setUndeliverable(PhorgeMetaMTAActor::REASON_UNLOADABLE);
      } else {
        $actor->setName($this->getUserName($user));
        if ($user->getIsDisabled()) {
          $actor->setUndeliverable(PhorgeMetaMTAActor::REASON_DISABLED);
        }
        if ($user->getIsSystemAgent()) {
          $actor->setUndeliverable(PhorgeMetaMTAActor::REASON_BOT);
        }

        // NOTE: We do send email to unapproved users, and to unverified users,
        // because it would otherwise be impossible to get them to verify their
        // email addresses. Possibly we should white-list this kind of mail and
        // deny all other types of mail.
      }

      $email = idx($emails, $phid);
      if (!$email) {
        $actor->setUndeliverable(PhorgeMetaMTAActor::REASON_NO_ADDRESS);
      } else {
        $actor->setEmailAddress($email->getAddress());
        $actor->setIsVerified($email->getIsVerified());
      }
    }
  }

  private function loadUnknownActors(array $actors, array $phids) {
    foreach ($phids as $phid) {
      $actor = $actors[$phid];
      $actor->setUndeliverable(PhorgeMetaMTAActor::REASON_UNMAILABLE);
    }
  }


  /**
   * Small helper function to make sure we format the username properly as
   * specified by the `metamta.user-address-format` configuration value.
   */
  private function getUserName(PhorgeUser $user) {
    $format = PhorgeEnv::getEnvConfig('metamta.user-address-format');

    switch ($format) {
      case 'short':
        $name = $user->getUserName();
        break;
      case 'real':
        $name = strlen($user->getRealName()) ?
          $user->getRealName() : $user->getUserName();
        break;
      case 'full':
      default:
        $name = $user->getFullName();
        break;
    }

    return $name;
  }

}
