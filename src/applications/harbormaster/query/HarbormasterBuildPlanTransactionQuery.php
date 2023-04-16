<?php

final class HarbormasterBuildPlanTransactionQuery
  extends PhorgeApplicationTransactionQuery {

  public function getTemplateApplicationTransaction() {
    return new HarbormasterBuildPlanTransaction();
  }

}
