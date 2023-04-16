<?php

final class PhorgeLegalpadDocumentSignaturePHIDType
  extends PhorgePHIDType {

  const TYPECONST = 'LEGS';

  public function getTypeName() {
    return pht('Legalpad Signature');
  }

  public function getTypeIcon() {
    return 'fa-file-text-o';
  }

  public function newObject() {
    return new LegalpadDocumentSignature();
  }

  public function getPHIDTypeApplicationClass() {
    return 'PhorgeLegalpadApplication';
  }

  protected function buildQueryForObjects(
    PhorgeObjectQuery $query,
    array $phids) {

    return id(new LegalpadDocumentSignatureQuery())
      ->withPHIDs($phids);
  }

  public function loadHandles(
    PhorgeHandleQuery $query,
    array $handles,
    array $objects) {

    foreach ($handles as $phid => $handle) {
      $sig = $objects[$phid];
      $id = $sig->getID();
      $handle->setName('Signature '.$id);

      $signer_name = $sig->getSignerName();
      $handle->setFullName("Signature {$id} by {$signer_name}");
      $handle->setURI("/legalpad/signature/{$id}");
    }
  }
}
