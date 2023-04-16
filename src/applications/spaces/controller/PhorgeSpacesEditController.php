<?php

final class PhorgeSpacesEditController
  extends PhorgeSpacesController {

  public function handleRequest(AphrontRequest $request) {
    $viewer = $request->getUser();

    $make_default = false;

    $id = $request->getURIData('id');
    if ($id) {
      $space = id(new PhorgeSpacesNamespaceQuery())
        ->setViewer($viewer)
        ->withIDs(array($id))
        ->requireCapabilities(
          array(
            PhorgePolicyCapability::CAN_VIEW,
            PhorgePolicyCapability::CAN_EDIT,
          ))
        ->executeOne();
      if (!$space) {
        return new Aphront404Response();
      }

      $is_new = false;
      $cancel_uri = '/'.$space->getMonogram();

      $header_text = pht('Edit %s', $space->getNamespaceName());
      $title = pht('Edit Space');
      $button_text = pht('Save Changes');
    } else {
      $this->requireApplicationCapability(
        PhorgeSpacesCapabilityCreateSpaces::CAPABILITY);

      $space = PhorgeSpacesNamespace::initializeNewNamespace($viewer);

      $is_new = true;
      $cancel_uri = $this->getApplicationURI();

      $header_text = pht('Create Space');
      $title = pht('Create Space');
      $button_text = pht('Create Space');

      $default = id(new PhorgeSpacesNamespaceQuery())
        ->setViewer(PhorgeUser::getOmnipotentUser())
        ->withIsDefaultNamespace(true)
        ->execute();
      if (!$default) {
        $make_default = true;
      }
    }

    $validation_exception = null;
    $e_name = true;
    $v_name = $space->getNamespaceName();
    $v_desc = $space->getDescription();
    $v_view = $space->getViewPolicy();
    $v_edit = $space->getEditPolicy();

    if ($request->isFormPost()) {
      $xactions = array();
      $e_name = null;

      $v_name = $request->getStr('name');
      $v_desc = $request->getStr('description');
      $v_view = $request->getStr('viewPolicy');
      $v_edit = $request->getStr('editPolicy');

      $type_name =
        PhorgeSpacesNamespaceNameTransaction::TRANSACTIONTYPE;
      $type_desc =
        PhorgeSpacesNamespaceDescriptionTransaction::TRANSACTIONTYPE;
      $type_default =
        PhorgeSpacesNamespaceDefaultTransaction::TRANSACTIONTYPE;
      $type_view = PhorgeTransactions::TYPE_VIEW_POLICY;
      $type_edit = PhorgeTransactions::TYPE_EDIT_POLICY;

      $xactions[] = id(new PhorgeSpacesNamespaceTransaction())
        ->setTransactionType($type_name)
        ->setNewValue($v_name);

      $xactions[] = id(new PhorgeSpacesNamespaceTransaction())
        ->setTransactionType($type_desc)
        ->setNewValue($v_desc);

      $xactions[] = id(new PhorgeSpacesNamespaceTransaction())
        ->setTransactionType($type_view)
        ->setNewValue($v_view);

      $xactions[] = id(new PhorgeSpacesNamespaceTransaction())
        ->setTransactionType($type_edit)
        ->setNewValue($v_edit);

      if ($make_default) {
        $xactions[] = id(new PhorgeSpacesNamespaceTransaction())
          ->setTransactionType($type_default)
          ->setNewValue(1);
      }

      $editor = id(new PhorgeSpacesNamespaceEditor())
        ->setActor($viewer)
        ->setContinueOnNoEffect(true)
        ->setContentSourceFromRequest($request);

      try {
        $editor->applyTransactions($space, $xactions);

        return id(new AphrontRedirectResponse())
          ->setURI('/'.$space->getMonogram());
      } catch (PhorgeApplicationTransactionValidationException $ex) {
        $validation_exception = $ex;

        $e_name = $ex->getShortMessage($type_name);
      }
    }

    $policies = id(new PhorgePolicyQuery())
      ->setViewer($viewer)
      ->setObject($space)
      ->execute();

    $form = id(new AphrontFormView())
      ->setUser($viewer);

    if ($make_default) {
      $form->appendRemarkupInstructions(
        pht(
          'NOTE: You are creating the **default space**. All existing '.
          'objects will be put into this space. You must create a default '.
          'space before you can create other spaces.'));
    }

    $form
      ->appendChild(
        id(new AphrontFormTextControl())
          ->setLabel(pht('Name'))
          ->setName('name')
          ->setValue($v_name)
          ->setError($e_name))
      ->appendControl(
        id(new PhorgeRemarkupControl())
          ->setLabel(pht('Description'))
          ->setName('description')
          ->setValue($v_desc))
      ->appendChild(
        id(new AphrontFormPolicyControl())
          ->setUser($viewer)
          ->setCapability(PhorgePolicyCapability::CAN_VIEW)
          ->setPolicyObject($space)
          ->setPolicies($policies)
          ->setValue($v_view)
          ->setName('viewPolicy'))
      ->appendChild(
        id(new AphrontFormPolicyControl())
          ->setUser($viewer)
          ->setCapability(PhorgePolicyCapability::CAN_EDIT)
          ->setPolicyObject($space)
          ->setPolicies($policies)
          ->setValue($v_edit)
          ->setName('editPolicy'))
      ->appendChild(
        id(new AphrontFormSubmitControl())
          ->setValue($button_text)
          ->addCancelButton($cancel_uri));

    $box = id(new PHUIObjectBoxView())
      ->setHeaderText($title)
      ->setBackground(PHUIObjectBoxView::WHITE_CONFIG)
      ->setValidationException($validation_exception)
      ->appendChild($form);

    $crumbs = $this->buildApplicationCrumbs();
    if (!$is_new) {
      $crumbs->addTextCrumb(
        $space->getMonogram(),
        $cancel_uri);
    }
    $crumbs->addTextCrumb($title);
    $crumbs->setBorder(true);

    $view = id(new PHUITwoColumnView())
      ->setFooter(array(
          $box,
        ));

    return $this->newPage()
      ->setTitle($title)
      ->setCrumbs($crumbs)
      ->appendChild($view);
  }
}
