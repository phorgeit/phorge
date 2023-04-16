<?php

final class HarbormasterBuildTransactionQuery
  extends PhorgeApplicationTransactionQuery {

  public function getTemplateApplicationTransaction() {
    return new HarbormasterBuildTransaction();
  }

}
