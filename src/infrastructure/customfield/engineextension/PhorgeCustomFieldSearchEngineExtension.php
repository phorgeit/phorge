<?php

final class PhorgeCustomFieldSearchEngineExtension
  extends PhorgeSearchEngineExtension {

  const EXTENSIONKEY = 'customfield';

  public function isExtensionEnabled() {
    return true;
  }

  public function getExtensionName() {
    return pht('Support for Custom Fields');
  }

  public function supportsObject($object) {
    return ($object instanceof PhorgeCustomFieldInterface);
  }

  public function getExtensionOrder() {
    return 9000;
  }

  public function getSearchFields($object) {
    $engine = $this->getSearchEngine();
    $custom_fields = $this->getCustomFields($object);

    $fields = array();
    foreach ($custom_fields as $field) {
      $fields[] = id(new PhorgeSearchCustomFieldProxyField())
        ->setSearchEngine($engine)
        ->setCustomField($field);
    }

    return $fields;
  }

  public function applyConstraintsToQuery(
    $object,
    $query,
    PhorgeSavedQuery $saved,
    array $map) {

    $engine = $this->getSearchEngine();
    $fields = $this->getCustomFields($object);

    foreach ($fields as $field) {
      $field->applyApplicationSearchConstraintToQuery(
        $engine,
        $query,
        $saved->getParameter('custom:'.$field->getFieldIndex()));
    }
  }

  private function getCustomFields($object) {
    $fields = PhorgeCustomField::getObjectFields(
      $object,
      PhorgeCustomField::ROLE_APPLICATIONSEARCH);
    $fields->setViewer($this->getViewer());

    return $fields->getFields();
  }

  public function getFieldSpecificationsForConduit($object) {
    $fields = PhorgeCustomField::getObjectFields(
      $object,
      PhorgeCustomField::ROLE_CONDUIT);

    $map = array();
    foreach ($fields->getFields() as $field) {
      $key = $field->getModernFieldKey();

      // TODO: These should have proper types.
      $map[] = id(new PhorgeConduitSearchFieldSpecification())
        ->setKey($key)
        ->setType('wild')
        ->setDescription($field->getFieldDescription());
    }

    return $map;
  }

  public function loadExtensionConduitData(array $objects) {
    $viewer = $this->getViewer();

    $field_map = array();
    foreach ($objects as $object) {
      $object_phid = $object->getPHID();

      $fields = PhorgeCustomField::getObjectFields(
        $object,
        PhorgeCustomField::ROLE_CONDUIT);

      $fields
        ->setViewer($viewer)
        ->readFieldsFromObject($object);

      $field_map[$object_phid] = $fields;
    }

    $all_fields = array();
    foreach ($field_map as $field_list) {
      foreach ($field_list->getFields() as $field) {
        $all_fields[] = $field;
      }
    }

    id(new PhorgeCustomFieldStorageQuery())
      ->addFields($all_fields)
      ->execute();

    return array(
      'fields' => $field_map,
    );
  }

  public function getFieldValuesForConduit($object, $data) {
    $fields = $data['fields'][$object->getPHID()];

    $map = array();
    foreach ($fields->getFields() as $field) {
      $key = $field->getModernFieldKey();
      $map[$key] = $field->getConduitDictionaryValue();
    }

    return $map;
  }

}
