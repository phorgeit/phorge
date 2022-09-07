<?php

$phid_type = PhabricatorLegalpadDocumentSignaturePHIDType::TYPECONST;

$docsig_table = new LegalpadDocumentSignature();

$conn = $docsig_table->establishConnection('w');
$table_name = $docsig_table->getTableName();

$chunk_size = 4096;

$temporary_table = 'tmp_20210802_docsig_id_map';

try {
  queryfx(
    $conn,
    'CREATE TEMPORARY TABLE %T (
      docsig_id INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
      docsig_phid VARBINARY(64) NOT NULL)',
    $temporary_table);
} catch (AphrontAccessDeniedQueryException $ex) {
  throw new PhutilProxyException(
    pht(
      'Failed to "CREATE TEMPORARY TABLE". You may need to "GRANT" the '.
      'current MySQL user this permission.'),
    $ex);
}

$table_iterator = id(new LiskRawMigrationIterator($conn, $table_name))
  ->setPageSize($chunk_size);

$chunk_iterator = new PhutilChunkedIterator($table_iterator, $chunk_size);
foreach ($chunk_iterator as $chunk) {

  $map = array();
  foreach ($chunk as $docsig_row) {
    $phid = $docsig_row['phid'];

    if (strlen($phid)) {
      continue;
    }

    $phid = PhabricatorPHID::generateNewPHID($phid_type);
    $id = $docsig_row['id'];

    $map[(int)$id] = $phid;
  }

  if (!$map) {
    continue;
  }

  $sql = array();
  foreach ($map as $docsig_id => $docsig_phid) {
    $sql[] = qsprintf(
      $conn,
      '(%d, %s)',
      $docsig_id,
      $docsig_phid);
  }

  queryfx(
    $conn,
    'TRUNCATE TABLE %T',
    $temporary_table);

  queryfx(
    $conn,
    'INSERT INTO %T (docsig_id, docsig_phid) VALUES %LQ',
    $temporary_table,
    $sql);

  queryfx(
    $conn,
    'UPDATE %T c JOIN %T x ON c.id = x.docsig_id
      SET c.phid = x.docsig_phid',
    $table_name,
    $temporary_table);
}
