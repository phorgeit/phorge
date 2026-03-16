<?php

final class PhorgePolicyViewNamedPolicyController
  extends PhabricatorNamedPolicyController {

  public function handleRequest(AphrontRequest $request) {
    $viewer = $request->getViewer();
    $id = $request->getURIData('id');

    $named_policy = id(new PhorgeNamedPolicyQuery())
      ->setViewer($viewer)
      ->withIDs(array($id))
      ->executeOne();
    if (!$named_policy) {
      return new Aphront404Response();
    }

    $crumbs = $this->buildApplicationCrumbs();
    $title = $named_policy->getName();

    $header = $this->buildHeaderView($named_policy);
    $curtain = $this->buildCurtain($named_policy);
    $details = $this->buildDetailsView($named_policy);

    $timeline = $this->buildTransactionTimeline(
      $named_policy,
      new PhorgePolicyNamedPolicyTransactionQuery());
    $comment_view = id(new PhorgeNamedPolicyEditEngine())
      ->setViewer($viewer)
      ->buildEditEngineCommentView($named_policy);

    $view = id(new PHUITwoColumnView())
      ->setHeader($header)
      ->setCurtain($curtain)
      ->setMainColumn(array(
          $timeline,
          $comment_view,
        ))
      ->addPropertySection(pht('Details'), $details);


    return $this->newPage()
      ->setTitle($title)
      ->setCrumbs($crumbs)
      ->setPageObjectPHIDs(array($named_policy->getPHID()))
      ->appendChild($view);
  }

  private function buildCurtain($named_policy) {
    $viewer = $this->getViewer();

    $can_edit = PhabricatorPolicyFilter::hasCapability(
      $viewer,
      $named_policy,
      PhabricatorPolicyCapability::CAN_EDIT);

    $curtain = $this->newCurtainView($named_policy);
    $id = $named_policy->getID();

    $edit_uri = $this->getApplicationURI("/named/edit/{$id}/");
    $curtain->addAction(
      id(new PhabricatorActionView())
        ->setName(pht('Edit Named Policy'))
        ->setIcon('fa-pencil')
        ->setDisabled(!$can_edit)
        ->setHref($edit_uri));


    return $curtain;
  }

  private function buildHeaderView($named_policy) {
    return id(new PHUIHeaderView())
      ->setViewer($this->getViewer())
      ->setPolicyObject($named_policy)
      ->setHeader($named_policy->getName());
  }

  private function buildDetailsView($named_policy) {
    $viewer = $this->getViewer();
    $view = id(new PHUIPropertyListView())
      ->setViewer($viewer);

    $applicable_to = $named_policy->getReferenceObjectPHIDType();
    if (!$applicable_to) {
      $applicable_to = pht('All object types');
    } else {
      $applicable_to = id(new PHUIIconView())
        ->setIcon($applicable_to->getTypeIcon())
        ->setText($applicable_to->getTypeName());
    }

    $view->addProperty(pht('Applicable To'), $applicable_to);

    $policy = $this->renderEffectivePolicy($named_policy);
    $view->addProperty(pht('Effective Policy'), $policy);


    $description = $named_policy->getDescription();
    if (strlen($description)) {
      $view
        ->addSectionHeader(pht('Description'))
        ->addTextContent(
          new PHUIRemarkupView($viewer, $description));
    }

    return $view;
  }

  private function renderEffectivePolicy(PhorgeNamedPolicy $named_policy) {

    $capability = PhorgeNamedPolicyEffectivePolicyCapability::CAPABILITY;

    $viewer = $this->getViewer();

    $policy = id(new PhabricatorPolicyQuery())
      ->setViewer($viewer)
      ->withPHIDs(array($named_policy->getEffectivePolicy()))
      ->executeOne();

    $object_phid = $named_policy->getPHID();

    $policy_name = $policy->getShortName();
    $policy_icon = $policy->getIcon().' bluegrey';

    $link = javelin_tag(
      'a',
      array(
        'class' => 'policy-link',
        'href' => '/policy/explain/'.$object_phid.'/'.$capability.'/',
        'sigil' => 'workflow',
      ),
      $policy_name);

    return id(new PHUIIconView())
        ->setIcon($policy_icon)
        ->setText($link);
  }

}
