<?php

abstract class NuanceWorker extends PhorgeWorker {

  protected function getViewer() {
    return PhorgeUser::getOmnipotentUser();
  }

  protected function loadItem($item_phid) {
    $item = id(new NuanceItemQuery())
      ->setViewer($this->getViewer())
      ->withPHIDs(array($item_phid))
      ->executeOne();

    if (!$item) {
      throw new PhorgeWorkerPermanentFailureException(
        pht(
          'There is no Nuance item with PHID "%s".',
          $item_phid));
    }

    return $item;
  }

}
