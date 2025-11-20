<?php

final class ConduitConstantDescription extends Phobject {

  private $key;
  private $value;
  private $isDeprecated;

  /**
   * @param string $key Key of the constant
   */
  public function setKey($key) {
    $this->key = $key;
    return $this;
  }

  public function getKey() {
    return $this->key;
  }

  /**
   * @param string $value Description of the constant
   */
  public function setValue($value) {
    $this->value = $value;
    return $this;
  }

  public function getValue() {
    return $this->value;
  }

  /**
   * @param bool $is_deprecated Whether the constant is deprecated
   */
  public function setIsDeprecated($is_deprecated) {
    $this->isDeprecated = $is_deprecated;
    return $this;
  }

  public function getIsDeprecated() {
    return $this->isDeprecated;
  }

}
