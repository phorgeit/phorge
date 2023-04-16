<?php

final class DiffusionCacheEngineExtension
  extends PhorgeCacheEngineExtension {

  const EXTENSIONKEY = 'diffusion';

  public function getExtensionName() {
    return pht('Diffusion Repositories');
  }

  public function discoverLinkedObjects(
    PhorgeCacheEngine $engine,
    array $objects) {
    $viewer = $engine->getViewer();
    $results = array();

    // When an Almanac Service changes, update linked repositories.

    $services = $this->selectObjects($objects, 'AlmanacService');
    if ($services) {
      $repositories = id(new PhorgeRepositoryQuery())
        ->setViewer($viewer)
        ->withAlmanacServicePHIDs(mpull($services, 'getPHID'))
        ->execute();
      foreach ($repositories as $repository) {
        $results[] = $repository;
      }
    }

    return $results;
  }

  public function deleteCaches(
    PhorgeCacheEngine $engine,
    array $objects) {

    $keys = array();
    $repositories = $this->selectObjects($objects, 'PhorgeRepository');
    foreach ($repositories as $repository) {
      $keys[] = $repository->getAlmanacServiceCacheKey();
    }

    $keys = array_filter($keys);

    if ($keys) {
      $cache = PhorgeCaches::getMutableStructureCache();
      $cache->deleteKeys($keys);
    }
  }

}
