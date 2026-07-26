<?php

final class PHUIDiffTableOfContentsItem extends Phobject {

  private $viewer;
  private $changeset;
  private $isVisible = true;
  private $anchor;
  private $coverage;
  private $coverageID;
  private $context;
  private $packages;

  public function setViewer(PhabricatorUser $viewer) {
    $this->viewer = $viewer;
    return $this;
  }

  public function getViewer() {
    return $this->viewer;
  }

  public function setChangeset(DifferentialChangeset $changeset) {
    $this->changeset = $changeset;
    return $this;
  }

  public function getChangeset() {
    return $this->changeset;
  }

  public function setIsVisible($is_visible) {
    $this->isVisible = $is_visible;
    return $this;
  }

  public function getIsVisible() {
    return $this->isVisible;
  }

  public function setAnchor($anchor) {
    $this->anchor = $anchor;
    return $this;
  }

  public function getAnchor() {
    return $this->anchor;
  }

  public function setCoverage($coverage) {
    $this->coverage = $coverage;
    return $this;
  }

  /**
   * Get the Coverage, expressed as a string, each letter with this meaning:
   * N: Not Executable, C: Covered, U: Uncovered.
   * @return string|null
   */
  public function getCoverage() {
    return $this->coverage;
  }

  public function setCoverageID($coverage_id) {
    $this->coverageID = $coverage_id;
    return $this;
  }

  public function getCoverageID() {
    return $this->coverageID;
  }

  public function setContext($context) {
    $this->context = $context;
    return $this;
  }

  public function getContext() {
    return $this->context;
  }

  /**
   * @param array<PhabricatorOwnersPackage> $packages
   */
  public function setPackages(array $packages) {
    assert_instances_of($packages, PhabricatorOwnersPackage::class);
    $this->packages = mpull($packages, null, 'getPHID');
    return $this;
  }

  public function getPackages() {
    return $this->packages;
  }

  /**
   * Create the string which shows how many lines were changed in a file.
   * @return PhutilSafeHTML
   */
  public function newLink() {
    $anchor = $this->getAnchor();

    $changeset = $this->getChangeset();
    $name = $changeset->getDisplayFilename();
    $name = basename($name);

    return javelin_tag(
      'a',
      array(
        'href' => '#'.$anchor,
        'sigil' => 'differential-load',
        'meta' => array(
          'id' => 'diff-'.$anchor,
        ),
      ),
      $name);
  }

  /**
   * Create the string which shows how many lines were changed in a file.
   * @return string|null
   */
  public function renderChangesetLines() {
    $changeset = $this->getChangeset();

    if ($changeset->getIsLowImportanceChangeset()) {
      return null;
    }

    $line_count = $changeset->getAffectedLineCount();
    if (!$line_count) {
      return null;
    }

    return pht('%s line(s)', new PhutilNumber($line_count));
  }

  public function renderCoverage() {
    $not_applicable = '-';

    $coverage = $this->getCoverage();
    if (!phutil_nonempty_string($coverage)) {
      return $not_applicable;
    }

    $covered = substr_count($coverage, 'C');
    $not_covered = substr_count($coverage, 'U');

    if (!$not_covered && !$covered) {
      return $not_applicable;
    }

    return sprintf('%d%%', 100 * ($covered / ($covered + $not_covered)));
  }

  /**
   * @return PhutilSafeHTML|string
   */
  public function renderModifiedCoverage() {
    $not_applicable = '-';

    $coverage = $this->getCoverage();
    if (!phutil_nonempty_string($coverage)) {
      return $not_applicable;
    }

    if ($this->getIsVisible()) {
      $label = pht('Loading...');
    } else {
      $label = pht('?');
    }

    return phutil_tag(
      'div',
      array(
        'id' => $this->getCoverageID(),
        'class' => 'differential-mcoverage-loading',
      ),
      $label);
  }

  /**
   * @return PHUIHandleListView|null View of the handles
   */
  public function renderPackages() {
    $packages = $this->getPackages();

    if (!$packages) {
      return null;
    }

    $viewer = $this->getViewer();
    $package_phids = mpull($packages, 'getPHID');

    return $viewer->renderHandleList($package_phids)
      ->setGlyphLimit(48);
  }

}
