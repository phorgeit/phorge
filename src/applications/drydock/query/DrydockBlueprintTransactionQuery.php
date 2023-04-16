<?php

final class DrydockBlueprintTransactionQuery
  extends PhorgeApplicationTransactionQuery {

  public function getTemplateApplicationTransaction() {
    return new DrydockBlueprintTransaction();
  }

}
