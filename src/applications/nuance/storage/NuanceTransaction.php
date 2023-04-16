<?php

abstract class NuanceTransaction
  extends PhorgeModularTransaction {

  public function getApplicationName() {
    return 'nuance';
  }

}
