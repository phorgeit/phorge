<?php

final class PhorgeIconSetEditField
  extends PhorgeEditField {

  private $iconSet;

  public function setIconSet(PhorgeIconSet $icon_set) {
    $this->iconSet = $icon_set;
    return $this;
  }

  public function getIconSet() {
    return $this->iconSet;
  }

  protected function newControl() {
    return id(new PHUIFormIconSetControl())
      ->setIconSet($this->getIconSet());
  }

  protected function newConduitParameterType() {
    return new ConduitStringParameterType();
  }

  protected function newHTTPParameterType() {
    return new AphrontStringHTTPParameterType();
  }

}
