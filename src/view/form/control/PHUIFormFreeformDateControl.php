<?php

final class PHUIFormFreeformDateControl extends AphrontFormControl {

  private $readOnly;

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
        'id'           => $this->getID(),
      ));
  }

}
