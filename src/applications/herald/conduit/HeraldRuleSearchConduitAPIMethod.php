<?php

final class HeraldRuleSearchConduitAPIMethod
  extends PhabricatorSearchEngineAPIMethod {

  public function getAPIMethodName() {
    return 'herald.rule.search';
  }

  public function newSearchEngine() {
    return new HeraldRuleSearchEngine();
  }

  public function getMethodSummary() {
    return pht('Retrieve information about Herald rules.');
  }

  public function isReadOnlyAPI() {
    return true;
  }

}
