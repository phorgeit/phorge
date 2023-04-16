<?php

final class PhorgeAuthFactorProviderViewController
  extends PhorgeAuthFactorProviderController {

  public function handleRequest(AphrontRequest $request) {
    $viewer = $this->getViewer();

    $this->requireApplicationCapability(
      AuthManageProvidersCapability::CAPABILITY);

    $provider = id(new PhorgeAuthFactorProviderQuery())
      ->setViewer($viewer)
      ->withIDs(array($request->getURIData('id')))
      ->executeOne();
    if (!$provider) {
      return new Aphront404Response();
    }

    $crumbs = $this->buildApplicationCrumbs()
      ->addTextCrumb($provider->getObjectName())
      ->setBorder(true);

    $header = $this->buildHeaderView($provider);
    $properties = $this->buildPropertiesView($provider);
    $curtain = $this->buildCurtain($provider);


    $timeline = $this->buildTransactionTimeline(
      $provider,
      new PhorgeAuthFactorProviderTransactionQuery());
    $timeline->setShouldTerminate(true);

    $view = id(new PHUITwoColumnView())
      ->setHeader($header)
      ->setCurtain($curtain)
      ->setMainColumn(
        array(
          $timeline,
        ))
      ->addPropertySection(pht('Details'), $properties);

    return $this->newPage()
      ->setTitle($provider->getDisplayName())
      ->setCrumbs($crumbs)
      ->setPageObjectPHIDs(
        array(
          $provider->getPHID(),
        ))
      ->appendChild($view);
  }

  private function buildHeaderView(PhorgeAuthFactorProvider $provider) {
    $viewer = $this->getViewer();

    $view = id(new PHUIHeaderView())
      ->setViewer($viewer)
      ->setHeader($provider->getDisplayName())
      ->setPolicyObject($provider);

    $status = $provider->newStatus();

    $header_icon = $status->getStatusHeaderIcon();
    $header_color = $status->getStatusHeaderColor();
    $header_name = $status->getName();
    if ($header_icon !== null) {
      $view->setStatus($header_icon, $header_color, $header_name);
    }

    return $view;
  }

  private function buildPropertiesView(
    PhorgeAuthFactorProvider $provider) {
    $viewer = $this->getViewer();

    $view = id(new PHUIPropertyListView())
      ->setViewer($viewer);

    $view->addProperty(
      pht('Factor Type'),
      $provider->getFactor()->getFactorName());


    $custom_enroll = $provider->getEnrollMessage();
    if (strlen($custom_enroll)) {
      $view->addSectionHeader(
        pht('Custom Enroll Message'),
        PHUIPropertyListView::ICON_SUMMARY);
      $view->addTextContent(
        new PHUIRemarkupView($viewer, $custom_enroll));
    }

    return $view;
  }

  private function buildCurtain(PhorgeAuthFactorProvider $provider) {
    $viewer = $this->getViewer();
    $id = $provider->getID();

    $can_edit = PhorgePolicyFilter::hasCapability(
      $viewer,
      $provider,
      PhorgePolicyCapability::CAN_EDIT);

    $curtain = $this->newCurtainView($provider);

    $curtain->addAction(
      id(new PhorgeActionView())
        ->setName(pht('Edit MFA Provider'))
        ->setIcon('fa-pencil')
        ->setHref($this->getApplicationURI("mfa/edit/{$id}/"))
        ->setDisabled(!$can_edit)
        ->setWorkflow(!$can_edit));

    $curtain->addAction(
      id(new PhorgeActionView())
        ->setName(pht('Customize Enroll Message'))
        ->setIcon('fa-commenting-o')
        ->setHref($this->getApplicationURI("mfa/message/{$id}/"))
        ->setDisabled(!$can_edit)
        ->setWorkflow(true));

    return $curtain;
  }

}
