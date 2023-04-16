<?php

final class HarbormasterBuildStepTransactionQuery
  extends PhorgeApplicationTransactionQuery {

  public function getTemplateApplicationTransaction() {
    return new HarbormasterBuildStepTransaction();
  }

}
