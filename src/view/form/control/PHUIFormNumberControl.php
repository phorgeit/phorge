<?php

final class PHUIFormNumberControl extends AphrontFormControl {

  private $disableAutocomplete;
  private $autofocus;
  private $readOnly;

  public function setDisableAutocomplete($disable_autocomplete) {
    $this->disableAutocomplete = $disable_autocomplete;
    return $this;
  }

  public function getDisableAutocomplete() {
    return $this->disableAutocomplete;
  }

  public function setAutofocus($autofocus) {
    $this->autofocus = $autofocus;
    return $this;
  }

  public function getAutofocus() {
    return $this->autofocus;
  }

  public function setReadOnly($read_only) {
    $this->readOnly = $read_only;
    return $this;
  }

  protected function getReadOnly() {
    return $this->readOnly;
  }

  protected function getCustomControlClass() {
    return 'phui-form-number';
  }

  protected function renderInput() {
    if ($this->getDisableAutocomplete()) {
      $autocomplete = 'off';
    } else {
      $autocomplete = null;
    }

    return javelin_tag(
      'input',
      array(
        'type' => 'text',
        'pattern' => '\d*',
        'name' => $this->getName(),
        'value' => $this->getValue(),
        'disabled' => $this->getDisabled() ? 'disabled' : null,
        'readonly' => $this->getReadOnly() ? 'readonly' : null,
        'autocomplete' => $autocomplete,
        'id' => $this->getID(),
        'autofocus' => ($this->getAutofocus() ? 'autofocus' : null),
      ));
  }

}
