<?php

final class PhorgeSpaceEditField
  extends PhorgeEditField {

  private $policyField;

  public function setPolicyField(PhorgePolicyEditField $policy_field) {
    $this->policyField = $policy_field;
    return $this;
  }

  public function getPolicyField() {
    return $this->policyField;
  }

  protected function newControl() {
    // NOTE: This field doesn't do anything on its own, it just serves as a
    // companion to the associated View Policy field.
    return null;
  }

  protected function newHTTPParameterType() {
    return new AphrontPHIDHTTPParameterType();
  }

  protected function newConduitParameterType() {
    return new ConduitPHIDParameterType();
  }

  public function shouldReadValueFromRequest() {
    return $this->getPolicyField()->shouldReadValueFromRequest();
  }

  public function shouldReadValueFromSubmit() {
    return $this->getPolicyField()->shouldReadValueFromSubmit();
  }

}
