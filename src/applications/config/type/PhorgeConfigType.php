<?php

abstract class PhorgeConfigType extends Phobject {

  final public function getTypeKey() {
    return $this->getPhobjectClassConstant('TYPEKEY');
  }

  final public static function getAllTypes() {
    return id(new PhutilClassMapQuery())
      ->setAncestorClass(__CLASS__)
      ->setUniqueMethod('getTypeKey')
      ->execute();
  }

  public function isValuePresentInRequest(
    PhorgeConfigOption $option,
    AphrontRequest $request) {
    $http_type = $this->newHTTPParameterType();
    return $http_type->getExists($request, 'value');
  }

  public function readValueFromRequest(
    PhorgeConfigOption $option,
    AphrontRequest $request) {
    $http_type = $this->newHTTPParameterType();
    return $http_type->getValue($request, 'value');
  }

  abstract protected function newHTTPParameterType();

  public function newTransaction(
    PhorgeConfigOption $option,
    $value) {

    $xaction_value = $this->newTransactionValue($option, $value);

    return id(new PhorgeConfigTransaction())
      ->setTransactionType(PhorgeConfigTransaction::TYPE_EDIT)
      ->setNewValue(
        array(
          'deleted' => false,
          'value' => $xaction_value,
        ));
  }

  protected function newTransactionValue(
    PhorgeConfigOption $option,
    $value) {
    return $value;
  }

  public function newDisplayValue(
    PhorgeConfigOption $option,
    $value) {
    return $value;
  }

  public function newControls(
    PhorgeConfigOption $option,
    $value,
    $error) {

    $control = $this->newControl($option)
      ->setError($error)
      ->setLabel(pht('Database Value'))
      ->setName('value');

    $value = $this->newControlValue($option, $value);
    $control->setValue($value);

    return array(
      $control,
    );
  }

  abstract protected function newControl(PhorgeConfigOption $option);

  protected function newControlValue(
    PhorgeConfigOption $option,
    $value) {
    return $value;
  }

  protected function newException($message) {
    return new PhorgeConfigValidationException($message);
  }

  public function newValueFromRequestValue(
    PhorgeConfigOption $option,
    $value) {
    return $this->newCanonicalValue($option, $value);
  }

  public function newValueFromCommandLineValue(
    PhorgeConfigOption $option,
    $value) {
    return $this->newCanonicalValue($option, $value);
  }

  protected function newCanonicalValue(
    PhorgeConfigOption $option,
    $value) {
    return $value;
  }

  abstract public function validateStoredValue(
    PhorgeConfigOption $option,
    $value);

}
