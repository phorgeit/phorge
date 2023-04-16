<?php

final class PhorgeEditEngineProfileMenuItem
  extends PhorgeProfileMenuItem {

  const MENUITEMKEY = 'editengine';

  const FIELD_FORM = 'formKey';

  private $form;

  public function getMenuItemTypeIcon() {
    return 'fa-plus';
  }

  public function getMenuItemTypeName() {
    return pht('Form');
  }

  public function canAddToObject($object) {
    return true;
  }

  public function attachForm($form) {
    $this->form = $form;
    return $this;
  }

  public function getForm() {
    $form = $this->form;
    if (!$form) {
      return null;
    }
    return $form;
  }

  public function willGetMenuItemViewList(array $items) {
    $viewer = $this->getViewer();
    $engines = PhorgeEditEngine::getAllEditEngines();
    $engine_keys = array_keys($engines);
    $forms = id(new PhorgeEditEngineConfigurationQuery())
      ->setViewer($viewer)
      ->withEngineKeys($engine_keys)
      ->withIsDisabled(false)
      ->execute();
    $form_engines = mgroup($forms, 'getEngineKey');
    $form_ids = $forms;

    $builtin_map = array();
    foreach ($form_engines as $engine_key => $form_engine) {
      $builtin_map[$engine_key] = mpull($form_engine, null, 'getBuiltinKey');
    }

    foreach ($items as $item) {
      $key = $item->getMenuItemProperty('formKey');
      list($engine_key, $form_key) = PhorgeEditEngine::splitFullKey($key);

      if (is_numeric($form_key)) {
        $form = idx($form_ids, $form_key, null);
        $item->getMenuItem()->attachForm($form);
      } else if (isset($builtin_map[$engine_key][$form_key])) {
        $form = $builtin_map[$engine_key][$form_key];
        $item->getMenuItem()->attachForm($form);
      }
    }
  }

  public function getDisplayName(
    PhorgeProfileMenuItemConfiguration $config) {
    $form = $this->getForm();
    if (!$form) {
      return pht('(Restricted/Invalid Form)');
    }
    if (strlen($this->getName($config))) {
      return $this->getName($config);
    } else {
      return $form->getName();
    }
  }

  public function buildEditEngineFields(
    PhorgeProfileMenuItemConfiguration $config) {
    return array(
      id(new PhorgeDatasourceEditField())
        ->setKey(self::FIELD_FORM)
        ->setLabel(pht('Form'))
        ->setIsRequired(true)
        ->setDatasource(new PhorgeEditEngineDatasource())
        ->setSingleValue($config->getMenuItemProperty('formKey')),
      id(new PhorgeTextEditField())
        ->setKey('name')
        ->setLabel(pht('Name'))
        ->setValue($this->getName($config)),
    );
  }

  private function getName(
    PhorgeProfileMenuItemConfiguration $config) {
    return $config->getMenuItemProperty('name');
  }

  protected function newMenuItemViewList(
    PhorgeProfileMenuItemConfiguration $config) {

    $form = $this->getForm();
    if (!$form) {
      return array();
    }

    $icon = $form->getIcon();
    $name = $this->getDisplayName($config);

    $uri = $form->getCreateURI();
    if ($uri === null) {
      return array();
    }

    $item = $this->newItemView()
      ->setURI($uri)
      ->setName($name)
      ->setIcon($icon);

    return array(
      $item,
    );
  }

  public function validateTransactions(
    PhorgeProfileMenuItemConfiguration $config,
    $field_key,
    $value,
    array $xactions) {

    $viewer = $this->getViewer();
    $errors = array();

    if ($field_key == self::FIELD_FORM) {
      if ($this->isEmptyTransaction($value, $xactions)) {
       $errors[] = $this->newRequiredError(
         pht('You must choose a form.'),
         $field_key);
      }

      foreach ($xactions as $xaction) {
        $new = $xaction['new'];

        if (!$new) {
          continue;
        }

        if ($new === $value) {
          continue;
        }

        list($engine_key, $form_key) = PhorgeEditEngine::splitFullKey(
          $new);

        $forms = id(new PhorgeEditEngineConfigurationQuery())
          ->setViewer($viewer)
          ->withEngineKeys(array($engine_key))
          ->withIdentifiers(array($form_key))
          ->execute();
        if (!$forms) {
          $errors[] = $this->newInvalidError(
            pht(
              'Form "%s" is not a valid form which you have permission to '.
              'see.',
              $new),
            $xaction['xaction']);
        }
      }
    }

    return $errors;
  }

}
