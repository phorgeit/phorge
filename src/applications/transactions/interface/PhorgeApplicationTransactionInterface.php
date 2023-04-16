<?php

/**
 * Allow infrastructure to apply transactions to the implementing object.
 *
 * For example, implementing this interface allows Subscriptions to apply CC
 * transactions, and allows Harbormaster to apply build result notifications.
 */
interface PhorgeApplicationTransactionInterface {

  /**
   * Return a @{class:PhorgeApplicationTransactionEditor} which can be
   * used to apply transactions to this object.
   *
   * @return PhorgeApplicationTransactionEditor Editor for this object.
   */
  public function getApplicationTransactionEditor();


  /**
   * Return a template transaction for this object.
   *
   * @return PhorgeApplicationTransaction
   */
  public function getApplicationTransactionTemplate();

}

// TEMPLATE IMPLEMENTATION /////////////////////////////////////////////////////


/* -(  PhorgeApplicationTransactionInterface  )------------------------- */
/*

  public function getApplicationTransactionEditor() {
    return new <<<???>>>Editor();
  }

  public function getApplicationTransactionTemplate() {
    return new <<<???>>>Transaction();
  }

*/
