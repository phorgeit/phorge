<?php

final class AlmanacDeletePropertyEditType
  extends PhorgeEditType {

  public function generateTransactions(
    PhorgeApplicationTransaction $template,
    array $spec) {

    $value = idx($spec, 'value');
    if (!is_array($value)) {
      throw new Exception(
        pht(
          'Transaction value when deleting Almanac properties must be a list '.
          'of property names.'));
    }

    $xactions = array();
    foreach ($value as $idx => $property_key) {
      if (!is_string($property_key)) {
        throw new Exception(
          pht(
            'When deleting Almanac properties, each property name must '.
            'be a string. The value at index "%s" is not a string.',
            $idx));
      }

      $xactions[] = $this->newTransaction($template)
        ->setMetadataValue('almanac.property', $property_key)
        ->setNewValue(true);
    }

    return $xactions;
  }

}
