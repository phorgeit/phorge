<?php

abstract class PhorgeTimelineEngine
  extends Phobject {

  private $viewer;
  private $object;
  private $xactions;
  private $viewData;

  final public static function newForObject($object) {
    if ($object instanceof PhorgeTimelineInterface) {
      $engine = $object->newTimelineEngine();
    } else {
      $engine = new PhorgeStandardTimelineEngine();
    }

    $engine->setObject($object);

    return $engine;
  }

  final public function setViewer(PhorgeUser $viewer) {
    $this->viewer = $viewer;
    return $this;
  }

  final public function getViewer() {
    return $this->viewer;
  }

  final public function setObject($object) {
    $this->object = $object;
    return $this;
  }

  final public function getObject() {
    return $this->object;
  }

  final public function setTransactions(array $xactions) {
    assert_instances_of($xactions, 'PhorgeApplicationTransaction');
    $this->xactions = $xactions;
    return $this;
  }

  final public function getTransactions() {
    return $this->xactions;
  }

  final public function setRequest(AphrontRequest $request) {
    $this->request = $request;
    return $this;
  }

  final public function getRequest() {
    return $this->request;
  }

  final public function setViewData(array $view_data) {
    $this->viewData = $view_data;
    return $this;
  }

  final public function getViewData() {
    return $this->viewData;
  }

  final public function buildTimelineView() {
    $view = $this->newTimelineView();

    if (!($view instanceof PhorgeApplicationTransactionView)) {
      throw new Exception(
        pht(
          'Expected "newTimelineView()" to return an object of class "%s" '.
          '(in engine "%s").',
          'PhorgeApplicationTransactionView',
          get_class($this)));
    }

    $viewer = $this->getViewer();
    $object = $this->getObject();
    $xactions = $this->getTransactions();

    return $view
      ->setViewer($viewer)
      ->setObject($object)
      ->setObjectPHID($object->getPHID())
      ->setTransactions($xactions);
  }

  protected function newTimelineView() {
    return new PhorgeApplicationTransactionView();
  }

}
