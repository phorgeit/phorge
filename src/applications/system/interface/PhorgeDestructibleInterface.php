<?php

interface PhorgeDestructibleInterface {

  public function destroyObjectPermanently(
    PhorgeDestructionEngine $engine);

}


// TEMPLATE IMPLEMENTATION /////////////////////////////////////////////////////


/* -(  PhorgeDestructibleInterface  )----------------------------------- */
/*

  public function destroyObjectPermanently(
    PhorgeDestructionEngine $engine) {

    <<<$this->nuke();>>>

  }

*/
