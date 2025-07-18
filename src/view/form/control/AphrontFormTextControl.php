<?php

final class AphrontFormTextControl extends AphrontFormControl {

  private $disableAutocomplete;
  private $sigil;
  private $placeholder;
  private $autofocus;
  private $readOnly;

  public function setDisableAutocomplete($disable) {
    $this->disableAutocomplete = $disable;
    return $this;
  }

  private function getDisableAutocomplete() {
    return $this->disableAutocomplete;
  }

  public function getPlaceholder() {
    return $this->placeholder;
  }

  public function setPlaceholder($placeholder) {
    $this->placeholder = $placeholder;
    return $this;
  }

  public function setAutofocus($autofocus) {
    $this->autofocus = $autofocus;
    return $this;
  }

  public function getAutofocus() {
    return $this->autofocus;
  }

  public function getSigil() {
    return $this->sigil;
  }

  public function setSigil($sigil) {
    $this->sigil = $sigil;
    return $this;
  }

  public function setReadOnly($read_only) {
    $this->readOnly = $read_only;
    return $this;
  }

  protected function getReadOnly() {
    return $this->readOnly;
  }

  protected function getCustomControlClass() {
    return 'aphront-form-control-text';
  }

  protected function renderInput() {
    return javelin_tag(
      'input',
      array(
        'type'         => 'text',
        'name'         => $this->getName(),
        'value'        => $this->getValue(),
        'disabled'     => $this->getDisabled() ? 'disabled' : null,
        'readonly'     => $this->getReadOnly() ? 'readonly' : null,
        'autocomplete' => $this->getDisableAutocomplete() ? 'off' : null,
        'id'           => $this->getID(),
        'sigil'        => $this->getSigil(),
        'placeholder'  => $this->getPlaceholder(),
        'autofocus' => ($this->getAutofocus() ? 'autofocus' : null),
      ));
  }

}
