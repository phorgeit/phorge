<?php

final class AlmanacDeviceTransactionQuery
  extends PhorgeApplicationTransactionQuery {

  public function getTemplateApplicationTransaction() {
    return new AlmanacDeviceTransaction();
  }

}
