<?php

final class PhabricatorChartFunctionLabel
  extends Phobject {

  private $key;
  private $name;
  private $color;
  private $icon;
  private $fillColor;

  public function setKey($key) {
    $this->key = $key;
    return $this;
  }

  /**
   * @return string Internal identifier of the line, e.g. "moved-in"
   */
  public function getKey() {
    return $this->key;
  }

  public function setName($name) {
    $this->name = $name;
    return $this;
  }

  /**
   * @return string User-visible label describing what the line represents,
   *   e.g. "Open Tasks"
   */
  public function getName() {
    return $this->name;
  }

  public function setColor($color) {
    $this->color = $color;
    return $this;
  }

  /**
   * @return string Color of the line, such as 'rgba(128, 128, 200, 1)'
   */
  public function getColor() {
    return $this->color;
  }

  public function setIcon($icon) {
    $this->icon = $icon;
    return $this;
  }

  public function getIcon() {
    return $this->icon;
  }

  public function setFillColor($fill_color) {
    $this->fillColor = $fill_color;
    return $this;
  }

  /**
   * @return string Color of the area, such as 'rgba(128, 128, 200, 0.15)'
   */
  public function getFillColor() {
    return $this->fillColor;
  }

  /**
   * @return array
   */
  public function toWireFormat() {
    return array(
      'key' => $this->getKey(),
      'name' => $this->getName(),
      'color' => $this->getColor(),
      'icon' => $this->getIcon(),
      'fillColor' => $this->getFillColor(),
    );
  }

}
