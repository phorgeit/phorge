<?php

interface PhorgeCustomFieldInterface {

  public function getCustomFieldBaseClass();
  public function getCustomFieldSpecificationForRole($role);
  public function getCustomFields();
  public function attachCustomFields(PhorgeCustomFieldAttachment $fields);

}


// TEMPLATE IMPLEMENTATION /////////////////////////////////////////////////////


/* -(  PhorgeCustomFieldInterface  )------------------------------------ */
/*

  private $customFields = self::ATTACHABLE;

  public function getCustomFieldSpecificationForRole($role) {
    return PhorgeEnv::getEnvConfig(<<<'application.fields'>>>);
  }

  public function getCustomFieldBaseClass() {
    return <<<<'YourApplicationHereCustomField'>>>>;
  }

  public function getCustomFields() {
    return $this->assertAttached($this->customFields);
  }

  public function attachCustomFields(PhorgeCustomFieldAttachment $fields) {
    $this->customFields = $fields;
    return $this;
  }

*/
