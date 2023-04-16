<?php

final class PhorgeOwnersHovercardEngineExtension
  extends PhorgeHovercardEngineExtension {

  const EXTENSIONKEY = 'owners';

  public function isExtensionEnabled() {
    return PhorgeApplication::isClassInstalled(
      'PhorgeOwnersApplication');
  }

  public function getExtensionName() {
    return pht('Owner Packages');
  }

  public function canRenderObjectHovercard($object) {
    return ($object instanceof PhorgeOwnersPackage);
  }

  public function willRenderHovercards(array $objects) {
    $viewer = $this->getViewer();
    $phids = mpull($objects, 'getPHID');

    $packages = id(new PhorgeOwnersPackageQuery())
      ->setViewer($viewer)
      ->withPHIDs($phids)
      ->execute();
    $packages = mpull($packages, null, 'getPHID');

    return array(
      'packages' => $packages,
    );
  }

  public function renderHovercard(
    PHUIHovercardView $hovercard,
    PhorgeObjectHandle $handle,
    $object,
    $data) {

    $viewer = $this->getViewer();

    $package = idx($data['packages'], $object->getPHID());
    if (!$package) {
      return;
    }

    $title = pht('%s: %s', 'O'.$package->getID(), $package->getName());
    $hovercard->setTitle($title);

    $dominion = $package->getDominion();
    $dominion_map = PhorgeOwnersPackage::getDominionOptionsMap();
    $spec = idx($dominion_map, $dominion, array());
    $name = idx($spec, 'short', $dominion);
    $hovercard->addField(pht('Dominion'), $name);

    $auto = $package->getAutoReview();
    $autoreview_map = PhorgeOwnersPackage::getAutoreviewOptionsMap();
    $spec = idx($autoreview_map, $auto, array());
    $name = idx($spec, 'name', $auto);
    $hovercard->addField(pht('Auto Review'), $name);

    if ($package->isArchived()) {
      $tag = id(new PHUITagView())
        ->setName(pht('Archived'))
        ->setColor(PHUITagView::COLOR_INDIGO)
        ->setType(PHUITagView::TYPE_OBJECT);
      $hovercard->addTag($tag);
    }

    $owner_phids = $package->getOwnerPHIDs();

    $hovercard->addField(
      pht('Owners'),
      $viewer->renderHandleList($owner_phids)->setAsInline(true));

    $description = $package->getDescription();
    if (strlen($description)) {
      $description = id(new PhutilUTF8StringTruncator())
        ->setMaximumGlyphs(120)
        ->truncateString($description);

      $hovercard->addField(pht('Description'), $description);
    }

  }

}
