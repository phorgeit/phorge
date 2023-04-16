<?php

abstract class PhorgeDestructibleCodex
  extends Phobject {

  private $viewer;
  private $object;

  public function getDestructionNotes() {
    return array();
  }

  final public function setViewer(PhorgeUser $viewer) {
    $this->viewer = $viewer;
    return $this;
  }

  final public function getViewer() {
    return $this->viewer;
  }

  final public function setObject(
    PhorgeDestructibleCodexInterface $object) {
    $this->object = $object;
    return $this;
  }

  final public function getObject() {
    return $this->object;
  }

  final public static function newFromObject(
    PhorgeDestructibleCodexInterface $object,
    PhorgeUser $viewer) {

    if (!($object instanceof PhorgeDestructibleInterface)) {
      throw new Exception(
        pht(
          'Object (of class "%s") implements interface "%s", but must also '.
          'implement interface "%s".',
          get_class($object),
          'PhorgeDestructibleCodexInterface',
          'PhorgeDestructibleInterface'));
    }

    $codex = $object->newDestructibleCodex();
    if (!($codex instanceof PhorgeDestructibleCodex)) {
      throw new Exception(
        pht(
          'Object (of class "%s") implements interface "%s", but defines '.
          'method "%s" incorrectly: this method must return an object of '.
          'class "%s".',
          get_class($object),
          'PhorgeDestructibleCodexInterface',
          'newDestructibleCodex()',
          __CLASS__));
    }

    $codex
      ->setObject($object)
      ->setViewer($viewer);

    return $codex;
  }

}
