<?php

final class PhorgeEditEngineConfigurationLockController
  extends PhorgeEditEngineController {

  public function handleRequest(AphrontRequest $request) {
    $engine_key = $request->getURIData('engineKey');
    $this->setEngineKey($engine_key);

    $key = $request->getURIData('key');
    $viewer = $this->getViewer();

    $config = id(new PhorgeEditEngineConfigurationQuery())
      ->setViewer($viewer)
      ->withEngineKeys(array($engine_key))
      ->withIdentifiers(array($key))
      ->requireCapabilities(
        array(
          PhorgePolicyCapability::CAN_VIEW,
          PhorgePolicyCapability::CAN_EDIT,
        ))
      ->executeOne();
    if (!$config) {
      return id(new Aphront404Response());
    }

    $cancel_uri = "/transactions/editengine/{$engine_key}/view/{$key}/";

    if ($request->isFormPost()) {
      $xactions = array();

      $locks = $request->getArr('locks');
      $type_locks = PhorgeEditEngineLocksTransaction::TRANSACTIONTYPE;
      $xactions[] = id(new PhorgeEditEngineConfigurationTransaction())
        ->setTransactionType($type_locks)
        ->setNewValue($locks);

      $editor = id(new PhorgeEditEngineConfigurationEditor())
        ->setActor($viewer)
        ->setContentSourceFromRequest($request)
        ->setContinueOnMissingFields(true)
        ->setContinueOnNoEffect(true);

      $editor->applyTransactions($config, $xactions);

      return id(new AphrontRedirectResponse())
        ->setURI($cancel_uri);
    }

    $engine = $config->getEngine();
    $fields = $engine->getFieldsForConfig($config);

    $help = pht(<<<EOTEXT
**Locked** fields are visible in the form, but their values can not be changed
by the user.

**Hidden** fields are not visible in the form.

Any assigned default values are still respected, even if the field is locked
or hidden.
EOTEXT
      );

    $form = id(new AphrontFormView())
      ->setUser($viewer)
      ->appendRemarkupInstructions($help);

    $locks = $config->getFieldLocks();

    $lock_visible = PhorgeEditEngineConfiguration::LOCK_VISIBLE;
    $lock_locked = PhorgeEditEngineConfiguration::LOCK_LOCKED;
    $lock_hidden = PhorgeEditEngineConfiguration::LOCK_HIDDEN;

    $map = array(
      $lock_visible => pht('Visible'),
      $lock_locked => pht("\xF0\x9F\x94\x92 Locked"),
      $lock_hidden => pht("\xE2\x9C\x98 Hidden"),
    );

    foreach ($fields as $field) {
      if (!$field->getIsFormField()) {
        continue;
      }

      if (!$field->getIsLockable()) {
        continue;
      }

      $key = $field->getKey();

      $label = $field->getLabel();
      if (!strlen($label)) {
        $label = $key;
      }

      if ($field->getIsHidden()) {
        $value = $lock_hidden;
      } else if ($field->getIsLocked()) {
        $value = $lock_locked;
      } else {
        $value = $lock_visible;
      }

      $form->appendControl(
        id(new AphrontFormSelectControl())
          ->setName('locks['.$key.']')
          ->setLabel($label)
          ->setValue($value)
          ->setOptions($map));
    }

    return $this->newDialog()
      ->setTitle(pht('Lock / Hide Fields'))
      ->setWidth(AphrontDialogView::WIDTH_FORM)
      ->appendForm($form)
      ->addSubmitButton(pht('Save Changes'))
      ->addCancelButton($cancel_uri);
  }

}
