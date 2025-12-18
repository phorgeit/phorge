<?php

final class PhabricatorMetaMTAMailableFunctionDatasource
  extends PhabricatorTypeaheadCompositeDatasource {

  public function getBrowseTitle() {
    return pht('Browse Subscribers');
  }

  public function getPlaceholderText() {
    if (id(new PhabricatorOwnersApplication())->isInstalled() ||
        id(new PhabricatorPackagesApplication())->isInstalled()) {
      return pht(
        'Type a username, project, mailing list, package, or function...');
    } else {
      return pht(
        'Type a username, project, mailing list, or function...');
    }
  }

  public function getDatasourceApplicationClass() {
    return PhabricatorMetaMTAApplication::class;
  }

  public function getComponentDatasources() {
    return array(
      new PhabricatorViewerDatasource(),
      new PhabricatorPeopleDatasource(),
      new PhabricatorProjectMembersDatasource(),
      new PhabricatorProjectDatasource(),
      new PhabricatorOwnersPackageDatasource(),
      new PhabricatorOwnersPackageOwnerDatasource(),
    );
  }

}
