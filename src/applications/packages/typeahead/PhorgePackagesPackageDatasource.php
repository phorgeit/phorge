<?php

final class PhorgePackagesPackageDatasource
  extends PhorgeTypeaheadDatasource {

  public function getBrowseTitle() {
    return pht('Browse Packages');
  }

  public function getPlaceholderText() {
    return pht('Type a package name...');
  }

  public function getDatasourceApplicationClass() {
    return 'PhorgePackagesApplication';
  }

  public function loadResults() {
    $viewer = $this->getViewer();
    $raw_query = $this->getRawQuery();

    $package_query = id(new PhorgePackagesPackageQuery());
    $packages = $this->executeQuery($package_query);

    $results = array();
    foreach ($packages as $package) {
      $results[] = id(new PhorgeTypeaheadResult())
        ->setName($package->getName())
        ->setPHID($package->getPHID());
    }

    return $this->filterResultsAgainstTokens($results);
  }

}
