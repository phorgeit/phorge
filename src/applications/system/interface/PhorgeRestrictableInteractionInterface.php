<?php

/**
 * Interface to disable numerous user interactions, for example to apply on
 * temporary objects
 */
interface PhorgeRestrictableInteractionInterface {

  /**
   * Whether to disallow numerous user interactions
   *
   * @return bool
   */
  public function disallowInteractions();

}
