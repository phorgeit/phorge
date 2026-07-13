<?php


final class PhorgeSystemDebugUIExtension extends PHUIActionListExtension {

  const EXTENSIONKEY = 'developeractions';

  public function shouldEnableForObject($object) {
    return $object && $object->getPHID();
  }

  public function getExtensionApplicationClass() {
    return PhabricatorSystemApplication::class;
  }

  protected function buildAction() {
    $viewer = $this->getViewer();
    $object = $this->getObject();

    $is_dev = $viewer->getUserSetting(PhorgeDeveloperToolsSettings::SETTINGKEY);
    if (!$is_dev) {
      return;
    }

    $phid = $object->getPHID();

    $submenu = array();

    $submenu[] = id(new PhabricatorActionView())
      ->setIcon('fa-asterisk')
      ->setName(pht('View Handle'))
      ->setHref(urisprintf('/search/handle/%s/', $phid))
      ->setWorkflow(true);

    $submenu[] = id(new PhabricatorActionView())
      ->setIcon('fa-address-card-o')
      ->setName(pht('View Hovercard'))
      ->setHref(urisprintf('/search/hovercard/?names=%s', $phid));

    $submenu[] = id(new PhabricatorActionView())
      ->setIcon('fa-list')
      ->setName(pht('View full transaction history'))
      ->setHref(urisprintf('/feed/transactions?objectPHIDs=%s', $phid));

    if ($object instanceof DifferentialRevision) {
      $submenu[] = id(new PhabricatorActionView())
        ->setIcon('fa-database')
        ->setName(pht('View Affected Path Index'))
        ->setHref(
          urisprintf(
            '/differential/revision/paths/%s/',
            $object->getID()));
    }

    return id(new PhabricatorActionView())
      ->setName(pht('Advanced/Developer...'))
      ->setIcon('fa-magic')
      ->setOrder(9001)
      ->setSubmenu($submenu);
  }

}
