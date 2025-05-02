<?php

final class HeraldEffect extends Phobject {

  private $objectPHID;
  private $action;
  private $target;
  private $rule;
  private $reason;

  public function setObjectPHID($object_phid) {
    $this->objectPHID = $object_phid;
    return $this;
  }

  /**
   * @return string PHID of the object that Herald is applied on
   */
  public function getObjectPHID() {
    return $this->objectPHID;
  }

  public function setAction($action) {
    $this->action = $action;
    return $this;
  }

  /**
   * @return string ACTIONCONST of the HeraldAction
   */
  public function getAction() {
    return $this->action;
  }

  public function setTarget($target) {
    $this->target = $target;
    return $this;
  }

  /**
   * @return array|null
   */
  public function getTarget() {
    return $this->target;
  }

  public function setRule(HeraldRule $rule) {
    $this->rule = $rule;
    return $this;
  }

  /**
   * @return HeraldRule
   */
  public function getRule() {
    return $this->rule;
  }

  public function setReason($reason) {
    $this->reason = $reason;
    return $this;
  }

  /**
   * @return string Reason why Herald effect was applied, for example
   *         "Conditions were met for H123 RuleName"
   */
  public function getReason() {
    return $this->reason;
  }

}
