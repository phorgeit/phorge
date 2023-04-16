<?php

final class PhorgeCustomFieldHeraldFieldGroup extends HeraldFieldGroup {

  const FIELDGROUPKEY = 'customfield';

  public function getGroupLabel() {
    return pht('Custom Fields');
  }

  protected function getGroupOrder() {
    return 2000;
  }

}
