<?php

final class PhabricatorCelerityTestCase extends PhabricatorTestCase {

  /**
   * This is more of an acceptance test case instead of a unit test. It verifies
   * that the Celerity map is up-to-date.
   */
  public function testCelerityMaps() {
    $resources_map = CelerityPhysicalResources::getAll();

    // The ../ hack works for Phorge, and that's good enough for now.
    $phorge_path = Filesystem::resolvePath(
      '../',
      phutil_get_library_root('phorge'));
    $phorge_path_len = strlen($phorge_path);

    foreach ($resources_map as $resources) {
      $map_path = $resources->getPathToMap();
      if (strncmp($phorge_path, $map_path, $phorge_path_len)) {
        // This resource is from another repository
        continue;
      }
      $old_map = new CelerityResourceMap($resources);

      $new_map = id(new CelerityResourceMapGenerator($resources))
        ->generate();

      // Don't actually compare these values with assertEqual(), since the diff
      // isn't helpful and is often enormously huge.

      $maps_are_identical =
        ($new_map->getNameMap() === $old_map->getNameMap()) &&
        ($new_map->getSymbolMap() === $old_map->getSymbolMap()) &&
        ($new_map->getRequiresMap() === $old_map->getRequiresMap()) &&
        ($new_map->getPackageMap() === $old_map->getPackageMap());

      $this->assertTrue(
        $maps_are_identical,
        pht(
          'When this test fails, it means the Celerity resource map is out '.
          'of date. Run `%s` to rebuild it.',
          'bin/celerity map'));
    }
  }

}
