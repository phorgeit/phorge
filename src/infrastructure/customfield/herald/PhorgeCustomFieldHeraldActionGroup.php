<?php

final class PhorgeCustomFieldHeraldActionGroup
  extends HeraldActionGroup {

  const ACTIONGROUPKEY = 'customfield';

  public function getGroupLabel() {
    return pht('Custom Fields');
  }

  protected function getGroupOrder() {
    return 2000;
  }

}
