<?php

final class PhabricatorConduitTokenEditController
  extends PhabricatorConduitController {

  public function handleRequest(AphrontRequest $request) {
    $viewer = $request->getViewer();
    $id = $request->getURIData('id');

    $errors = array();
    $e_name = true;

    if ($id) {
      $token = id(new PhabricatorConduitTokenQuery())
        ->setViewer($viewer)
        ->withIDs(array($id))
        ->withExpired(false)
        ->requireCapabilities(
          array(
            PhabricatorPolicyCapability::CAN_VIEW,
            PhabricatorPolicyCapability::CAN_EDIT,
          ))
        ->executeOne();
      if (!$token) {
        return new Aphront404Response();
      }

      $object = $token->getObject();

      $is_new = false;
      $title = pht('Edit API Token');

      if ($request->isFormPost()) {
        $new_name = $request->getStr('name');
        $token_length = $token->getColumnMaximumByteLength('tokenName');
        if (!phutil_nonempty_string($new_name)) {
          $e_name = pht('Required');
          $errors[] = pht(
            'Tokens must have a name.');
        } else if (strlen($new_name) > $token_length) {
          $errors[] = pht(
            'Maximum token name length is %d characters.',
            $token_length);
        }
        if (!$errors) {
          $token->setTokenName($new_name);
        }
      }
    } else {
      $object = id(new PhabricatorObjectQuery())
        ->setViewer($viewer)
        ->withPHIDs(array($request->getStr('objectPHID')))
        ->requireCapabilities(
          array(
            PhabricatorPolicyCapability::CAN_VIEW,
            PhabricatorPolicyCapability::CAN_EDIT,
          ))
        ->executeOne();
      if (!$object) {
        return new Aphront404Response();
      }

      $token = PhabricatorConduitToken::initializeNewToken(
        $object->getPHID(),
        PhabricatorConduitToken::TYPE_STANDARD);

      $is_new = true;
      $title = pht('Generate API Token');
      $submit_button = pht('Generate Token');
    }

    $panel_uri = id(new PhabricatorConduitTokensSettingsPanel())
      ->setViewer($viewer)
      ->setUser($object)
      ->getPanelURI();

    id(new PhabricatorAuthSessionEngine())->requireHighSecuritySession(
      $viewer,
      $request,
      $panel_uri);

    if ($request->isFormPost() && !$errors) {
      $token->save();

      if ($is_new) {
        $token_uri = '/conduit/token/edit/'.$token->getID().'/';
      } else {
        $token_uri = $panel_uri;
      }

      return id(new AphrontRedirectResponse())->setURI($token_uri);
    }

    $dialog = $this->newDialog()
      ->setTitle($title)
      ->setErrors($errors)
      ->addHiddenInput('objectPHID', $object->getPHID());

    if ($is_new) {
      $dialog
        ->appendParagraph(pht('Generate a new API token?'))
        ->addSubmitButton($submit_button)
        ->addCancelButton($panel_uri);
    } else {
      if ($token->getTokenType() === PhabricatorConduitToken::TYPE_CLUSTER) {
        $dialog->appendChild(
          pht(
            'This token is automatically generated, and used to make '.
            'requests between nodes in a cluster. You can not use this '.
            'token in external applications.'));
      } else {
        Javelin::initBehavior('select-on-click');
        $form = id(new AphrontFormView())
          ->setUser($viewer)
          ->appendChild(
            id(new AphrontFormTextControl())
              ->setLabel(pht('Token'))
              ->setReadOnly(true)
              ->setSigil('select-on-click')
              ->setHasCopyButton(true)
              ->setValue($token->getToken()));
        $form->appendChild(
          id(new AphrontFormTextControl())
            ->setName('name')
            ->setLabel(pht('Token Name'))
            ->setValue($token->getTokenName())
            ->setError($e_name));

        $dialog->appendForm($form);

        $submit_button = pht('Done');
        $dialog->addSubmitButton($submit_button);
      }

    }

    return $dialog;
  }

}
