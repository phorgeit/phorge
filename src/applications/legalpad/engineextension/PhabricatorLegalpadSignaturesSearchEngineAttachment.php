<?php

final class PhabricatorLegalpadSignaturesSearchEngineAttachment
  extends PhabricatorSearchEngineAttachment {

  public function getAttachmentName() {
    return pht('Document signers');
  }

  public function getAttachmentDescription() {
    return pht('Get the signer list for the project.');
  }

  public function willLoadAttachmentData($query, $spec) {
    $query->needSignatures(true);
  }

  public function getAttachmentForObject($object, $data, $spec) {
    $signatures = array();
    foreach ($object->getSignatures() as $signature) {
      $signatures[] = array(
        'phid' => $signature->getPHID(),
        'signerPHID' => $signature->getSignerPHID(),
        'exemptionPHID' => $signature->getExemptionPHID(),
        'isExemption' => $signature->getIsExemption(),
        'signerName' => $signature->getSignerName(),
        'signerEmail' => $signature->getSignerEmail(),
        'documentVersion' => $signature->getDocumentVersion(),
        'dateCreated' => (int)$signature->getDateCreated(),
      );
    }

    return array(
      'signatures' => $signatures,
    );
  }

}
