<?php

/**
 * @phutil-external-symbol class PhpParser\JsonDecoder
 */
final class PhorgePHPASTParseTree extends PhabricatorXHPASTDAO {

  protected $authorPHID;
  protected $input;
  protected $tree;
  protected $error;
  protected $tokenStream;

  protected function getConfiguration() {
    return array(
      self::CONFIG_SERIALIZATION => array(
        'tokenStream' => self::SERIALIZATION_PHP,
        'tree' => self::SERIALIZATION_JSON,
      ),
      self::CONFIG_BINARY => array(
        'tokenStream' => true,
      ),
      self::CONFIG_COLUMN_SCHEMA => array(
        'authorPHID' => 'phid?',
        'error' => 'text?',
        'input' => 'text',
      ),
    ) + parent::getConfiguration();
  }

  protected function applyLiskDataSerialization(array &$data, $deserialize) {
    // applyLiskDataSerialization overwrites $data, so capture the
    // JSON before it does so.
    $tree = $data['tree'];

    parent::applyLiskDataSerialization($data, $deserialize);

    if ($deserialize) {
      $data['tree'] = id(new PhpParser\JsonDecoder())->decode($tree);
    }
  }

  public function getTableName() {
    return 'phpast_parsetree';
  }
}
