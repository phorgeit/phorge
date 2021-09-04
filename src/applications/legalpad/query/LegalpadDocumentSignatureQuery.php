<?php

final class LegalpadDocumentSignatureQuery
  extends PhabricatorCursorPagedPolicyAwareQuery {

  private $ids;
  private $phids;
  private $documentPHIDs;
  private $signerPHIDs;
  private $documentVersions;
  private $secretKeys;
  private $nameContains;
  private $emailContains;

  public function withIDs(array $ids) {
    $this->ids = $ids;
    return $this;
  }

  public function withPHIDs(array $phids) {
    $this->phids = $phids;
    return $this;
  }

  public function withDocumentPHIDs(array $phids) {
    $this->documentPHIDs = $phids;
    return $this;
  }

  public function withSignerPHIDs(array $phids) {
    $this->signerPHIDs = $phids;
    return $this;
  }

  public function withDocumentVersions(array $versions) {
    $this->documentVersions = $versions;
    return $this;
  }

  public function withSecretKeys(array $keys) {
    $this->secretKeys = $keys;
    return $this;
  }

  public function withNameContains($text) {
    $this->nameContains = $text;
    return $this;
  }

  public function withEmailContains($text) {
    $this->emailContains = $text;
    return $this;
  }

  public function newResultObject() {
    return new LegalpadDocumentSignature();
  }

  protected function loadPage() {
    $table = $this->newResultObject();
    $data = $this->loadStandardPageRows($table);
    $signatures = $table->loadAllFromArray($data);
    return $signatures;
  }

  protected function willFilterPage(array $signatures) {
    $document_phids = mpull($signatures, 'getDocumentPHID');

    $documents = id(new LegalpadDocumentQuery())
      ->setParentQuery($this)
      ->setViewer($this->getViewer())
      ->withPHIDs($document_phids)
      ->execute();
    $documents = mpull($documents, null, 'getPHID');

    foreach ($signatures as $key => $signature) {
      $document_phid = $signature->getDocumentPHID();
      $document = idx($documents, $document_phid);
      if ($document) {
        $signature->attachDocument($document);
      } else {
        unset($signatures[$key]);
      }
    }

    return $signatures;
  }

  protected function buildWhereClause(AphrontDatabaseConnection $conn) {
    $where = array();

    $where[] = $this->buildPagingClause($conn);

    if ($this->ids !== null) {
      $where[] = qsprintf(
        $conn,
        'id IN (%Ld)',
        $this->ids);
    }

    if ($this->phids !== null) {
      $where[] = qsprintf(
        $conn,
        'phid IN (%Ls)',
        $this->phids);
    }

    if ($this->documentPHIDs !== null) {
      $where[] = qsprintf(
        $conn,
        'documentPHID IN (%Ls)',
        $this->documentPHIDs);
    }

    if ($this->signerPHIDs !== null) {
      $where[] = qsprintf(
        $conn,
        'signerPHID IN (%Ls)',
        $this->signerPHIDs);
    }

    if ($this->documentVersions !== null) {
      $where[] = qsprintf(
        $conn,
        'documentVersion IN (%Ld)',
        $this->documentVersions);
    }

    if ($this->secretKeys !== null) {
      $where[] = qsprintf(
        $conn,
        'secretKey IN (%Ls)',
        $this->secretKeys);
    }

    if ($this->nameContains !== null) {
      $where[] = qsprintf(
        $conn,
        'signerName LIKE %~',
        $this->nameContains);
    }

    if ($this->emailContains !== null) {
      $where[] = qsprintf(
        $conn,
        'signerEmail LIKE %~',
        $this->emailContains);
    }

    return $this->formatWhereClause($conn, $where);
  }

  public function getQueryApplicationClass() {
    return 'PhabricatorLegalpadApplication';
  }

}
