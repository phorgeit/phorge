<?php

abstract class PhabricatorNamedPolicyController
  extends PhabricatorPolicyController {


  protected function buildApplicationCrumbs() {
    $crumbs = parent::buildApplicationCrumbs();
    $crumbs->addTextCrumb(pht('Named Policy'), '/policy/named/');
    return $crumbs;
  }

}
