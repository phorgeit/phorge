<?php

interface PhorgeDraftInterface {

  public function newDraftEngine();

  public function getHasDraft(PhorgeUser $viewer);
  public function attachHasDraft(PhorgeUser $viewer, $has_draft);

}

/* -(  PhorgeDraftInterface  )------------------------------------------ */
/*

  public function newDraftEngine() {
    return new <...>DraftEngine();
  }

  public function getHasDraft(PhorgeUser $viewer) {
    return $this->assertAttachedKey($this->drafts, $viewer->getCacheFragment());
  }

  public function attachHasDraft(PhorgeUser $viewer, $has_draft) {
    $this->drafts[$viewer->getCacheFragment()] = $has_draft;
    return $this;
  }

*/
