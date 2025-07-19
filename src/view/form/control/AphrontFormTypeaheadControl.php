<?php

final class AphrontFormTypeaheadControl extends AphrontFormControl {

  private $hardpointID;
  private $placeholder;
  private $readonly;

  public function setHardpointID($hardpoint_id) {
    $this->hardpointID = $hardpoint_id;
    return $this;
  }

  public function getHardpointID() {
    return $this->hardpointID;
  }

  public function setPlaceholder($placeholder) {
    $this->placeholder = $placeholder;
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
    return 'aphront-form-control-typeahead';
  }

  protected function renderInput() {
    return javelin_tag(
      'div',
      array(
        'style' => 'position: relative;',
        'id' => $this->getHardpointID(),
      ),
      javelin_tag(
        'input',
        array(
          'type'         => 'text',
          'name'         => $this->getName(),
          'value'        => $this->getValue(),
          'placeholder'  => $this->placeholder,
          'disabled'     => $this->getDisabled() ? 'disabled' : null,
          'readonly'     => $this->getReadOnly() ? 'readonly' : null,
          'autocomplete' => 'off',
          'id'           => $this->getID(),
        )));
  }

}
