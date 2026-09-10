<?php

final class PhorgeRemarkupControlAction extends Phobject {

  private $icon;
  private $actionCode;
  private $href = '#';
  private $tooltip;
  private $align;
  private $nodevice = false;

  private $isSpacer;

  /** @return self */
  public static function newSpacer() {
    $spacer = new self();
    $spacer->isSpacer = true;
    return $spacer;
  }

  public function setIcon($icon) {
    $this->icon = $icon;
    return $this;
  }

  public function getIcon() {
    return $this->icon;
  }

  public function setActionCode($action_code) {
    $this->actionCode = $action_code;
    return $this;
  }

  public function getActionCode() {
    return $this->actionCode;
  }

  public function setHref($href) {
    $this->href = $href;
    return $this;
  }

  public function getHref() {
    return $this->href;
  }

  public function setTooltip($tooltip) {
    $this->tooltip = $tooltip;
    return $this;
  }

  public function getTooltip() {
    return $this->tooltip;
  }

  public function setNodevice($nodevice) {
    $this->nodevice = $nodevice;
    return $this;
  }

  public function getNodevice() {
    return $this->nodevice;
  }

  public function getIsSpacer() {
    return $this->isSpacer;
  }

  public function setAlign($align) {
    $this->align = $align;
    return $this;
  }

  public function getAlign() {
    return $this->align;
  }

}
