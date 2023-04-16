<?php

final class PhorgeAuthSSHKeyViewController
  extends PhorgeAuthSSHKeyController {

  public function shouldAllowPublic() {
    return true;
  }

  public function handleRequest(AphrontRequest $request) {
    $viewer = $request->getViewer();

    $id = $request->getURIData('id');

    $ssh_key = id(new PhorgeAuthSSHKeyQuery())
      ->setViewer($viewer)
      ->withIDs(array($id))
      ->executeOne();
    if (!$ssh_key) {
      return new Aphront404Response();
    }

    $this->setSSHKeyObject($ssh_key->getObject());

    $title = pht('SSH Key %d', $ssh_key->getID());

    $curtain = $this->buildCurtain($ssh_key);
    $details = $this->buildPropertySection($ssh_key);

    $header = id(new PHUIHeaderView())
      ->setUser($viewer)
      ->setHeader($ssh_key->getName())
      ->setHeaderIcon('fa-key');

    if ($ssh_key->getIsActive()) {
      $header->setStatus('fa-check', 'bluegrey', pht('Active'));
    } else {
      $header->setStatus('fa-ban', 'dark', pht('Revoked'));
    }

    $header->addActionLink(
      id(new PHUIButtonView())
        ->setTag('a')
        ->setText(pht('View Active Keys'))
        ->setHref($ssh_key->getObject()->getSSHPublicKeyManagementURI($viewer))
        ->setIcon('fa-list-ul'));

    $crumbs = $this->buildApplicationCrumbs();
    $crumbs->addTextCrumb($title);
    $crumbs->setBorder(true);

    $timeline = $this->buildTransactionTimeline(
      $ssh_key,
      new PhorgeAuthSSHKeyTransactionQuery());
    $timeline->setShouldTerminate(true);

    $view = id(new PHUITwoColumnView())
      ->setHeader($header)
      ->setCurtain($curtain)
      ->setMainColumn(
        array(
          $details,
          $timeline,
        ));

    return $this->newPage()
      ->setTitle($title)
      ->setCrumbs($crumbs)
      ->appendChild($view);
  }

  private function buildCurtain(PhorgeAuthSSHKey $ssh_key) {
    $viewer = $this->getViewer();

    $can_edit = PhorgePolicyFilter::hasCapability(
      $viewer,
      $ssh_key,
      PhorgePolicyCapability::CAN_EDIT);

    $id = $ssh_key->getID();

    $edit_uri = $this->getApplicationURI("sshkey/edit/{$id}/");
    $revoke_uri = $this->getApplicationURI("sshkey/revoke/{$id}/");

    $curtain = $this->newCurtainView($ssh_key);

    $curtain->addAction(
      id(new PhorgeActionView())
        ->setIcon('fa-pencil')
        ->setName(pht('Edit SSH Key'))
        ->setHref($edit_uri)
        ->setWorkflow(true)
        ->setDisabled(!$can_edit));

    $curtain->addAction(
      id(new PhorgeActionView())
        ->setIcon('fa-times')
        ->setName(pht('Revoke SSH Key'))
        ->setHref($revoke_uri)
        ->setWorkflow(true)
        ->setDisabled(!$can_edit));

    return $curtain;
  }

  private function buildPropertySection(
    PhorgeAuthSSHKey $ssh_key) {
    $viewer = $this->getViewer();

    $properties = id(new PHUIPropertyListView())
      ->setUser($viewer);

    $properties->addProperty(pht('SSH Key Type'), $ssh_key->getKeyType());
    $properties->addProperty(
      pht('Created'),
      phorge_datetime($ssh_key->getDateCreated(), $viewer));

    return id(new PHUIObjectBoxView())
      ->setHeaderText(pht('Details'))
      ->setBackground(PHUIObjectBoxView::BLUE_PROPERTY)
      ->appendChild($properties);
  }

}
