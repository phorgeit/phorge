<?php

abstract class PhorgeFact extends Phobject {

  private $key;

  public static function getAllFacts() {
    $engines = PhorgeFactEngine::loadAllEngines();

    $map = array();
    foreach ($engines as $engine) {
      $facts = $engine->newFacts();
      $facts = mpull($facts, null, 'getKey');
      $map += $facts;
    }

    return $map;
  }

  final public function setKey($key) {
    $this->key = $key;
    return $this;
  }

  final public function getKey() {
    return $this->key;
  }

  final public function getName() {
    return pht('Fact "%s"', $this->getKey());
  }

  final public function newDatapoint() {
    return $this->newTemplateDatapoint()
      ->setKey($this->getKey());
  }

  abstract protected function newTemplateDatapoint();

  final public function getFunctionArguments() {
    $key = $this->getKey();

    $argv = array();

    if (preg_match('/\.project\z/', $key)) {
      $argv[] = id(new PhorgeChartFunctionArgument())
        ->setName('phid')
        ->setType('phid');
    }

    if (preg_match('/\.owner\z/', $key)) {
      $argv[] = id(new PhorgeChartFunctionArgument())
        ->setName('phid')
        ->setType('phid');
    }

    return $argv;
  }

  final public function buildWhereClauseParts(
    AphrontDatabaseConnection $conn,
    PhorgeChartFunctionArgumentParser $arguments) {
    $where = array();

    $has_phid = $this->getFunctionArguments();

    if ($has_phid) {
      $phid = $arguments->getArgumentValue('phid');

      $dimension_id = id(new PhorgeFactObjectDimension())
        ->newDimensionID($phid);

      $where[] = qsprintf(
        $conn,
        'dimensionID = %d',
        $dimension_id);
    }

    return $where;
  }


}
