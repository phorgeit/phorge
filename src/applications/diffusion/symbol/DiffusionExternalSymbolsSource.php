<?php

abstract class DiffusionExternalSymbolsSource extends Phobject {

  /**
   * @return list of PhorgeRepositorySymbol
   */
  abstract public function executeQuery(DiffusionExternalSymbolQuery $query);

  protected function buildExternalSymbol() {
    return id(new PhorgeRepositorySymbol())
      ->setIsExternal(true)
      ->makeEphemeral();
  }
}
