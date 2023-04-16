<?php

abstract class ConpherenceConduitAPIMethod extends ConduitAPIMethod {

  final public function getApplication() {
    return PhorgeApplication::getByClass(
      'PhorgeConpherenceApplication');
  }

  final protected function getConpherenceURI(ConpherenceThread $conpherence) {
    $id = $conpherence->getID();
    return PhorgeEnv::getProductionURI(
      $this->getApplication()->getApplicationURI($id));
  }

}
