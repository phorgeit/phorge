<?php

final class PhorgePackagesVersionListView
  extends PhorgePackagesView {

  private $versions;

  public function setVersions(array $versions) {
    assert_instances_of($versions, 'PhorgePackagesVersion');
    $this->versions = $versions;
    return $this;
  }

  public function getVersions() {
    return $this->versions;
  }

  public function render() {
    return $this->newListView();
  }

  public function newListView() {
    $viewer = $this->getViewer();
    $versions = $this->getVersions();

    $list = id(new PHUIObjectItemListView())
      ->setViewer($viewer);

    foreach ($versions as $version) {
      $item = id(new PHUIObjectItemView())
        ->setHeader($version->getName())
        ->setHref($version->getURI());

      $list->addItem($item);
    }

    return $list;
  }

}
