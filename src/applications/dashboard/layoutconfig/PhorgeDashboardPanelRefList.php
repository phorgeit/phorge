<?php

final class PhorgeDashboardPanelRefList
  extends Phobject {

  private $refs;
  private $columns;

  public static function newFromDictionary($config) {
    if (!is_array($config)) {
      $config = array();
    }

    $mode_map = PhorgeDashboardLayoutMode::getAllLayoutModes();
    $mode_key = idx($config, 'layoutMode');
    if (!isset($mode_map[$mode_key])) {
      $mode_key = head_key($mode_map);
    }
    $mode = $mode_map[$mode_key];

    $columns = $mode->getLayoutModeColumns();
    $columns = mpull($columns, null, 'getColumnKey');
    $default_column = head($columns);

    $panels = idx($config, 'panels');
    if (!is_array($panels)) {
      $panels = array();
    }

    $seen_panels = array();
    $refs = array();
    foreach ($panels as $panel) {
      $panel_phid = idx($panel, 'panelPHID');
      if (!strlen($panel_phid)) {
        continue;
      }

      $panel_key = idx($panel, 'panelKey');
      if (!strlen($panel_key)) {
        continue;
      }

      if (isset($seen_panels[$panel_key])) {
        continue;
      }
      $seen_panels[$panel_key] = true;

      $column_key = idx($panel, 'columnKey');
      $column = idx($columns, $column_key, $default_column);

      $ref = id(new PhorgeDashboardPanelRef())
        ->setPanelPHID($panel_phid)
        ->setPanelKey($panel_key)
        ->setColumnKey($column->getColumnKey());

      $column->addPanelRef($ref);
      $refs[] = $ref;
    }

    $list = new self();

    $list->columns = $columns;
    $list->refs = $refs;

    return $list;
  }

  public function getColumns() {
    return $this->columns;
  }

  public function getPanelRefs() {
    return $this->refs;
  }

  public function getPanelRef($panel_key) {
    foreach ($this->getPanelRefs() as $ref) {
      if ($ref->getPanelKey() === $panel_key) {
        return $ref;
      }
    }

    return null;
  }

  public function toDictionary() {
    return array_values(mpull($this->getPanelRefs(), 'toDictionary'));
  }

  public function newPanelRef(
    PhorgeDashboardPanel $panel,
    $column_key = null) {

    if ($column_key === null) {
      $column_key = head_key($this->columns);
    }

    $ref = id(new PhorgeDashboardPanelRef())
      ->setPanelKey($this->newPanelKey())
      ->setPanelPHID($panel->getPHID())
      ->setColumnKey($column_key);

    $this->refs[] = $ref;

    return $ref;
  }

  public function removePanelRef(PhorgeDashboardPanelRef $target) {
    foreach ($this->refs as $key => $ref) {
      if ($ref->getPanelKey() !== $target->getPanelKey()) {
        continue;
      }

      unset($this->refs[$key]);
      return $ref;
    }

    return null;
  }

  public function movePanelRef(
    PhorgeDashboardPanelRef $target,
    $column_key,
    PhorgeDashboardPanelRef $after = null) {

    $target->setColumnKey($column_key);

    $results = array();

    if (!$after) {
      $results[] = $target;
    }

    foreach ($this->refs as $ref) {
      if ($ref->getPanelKey() === $target->getPanelKey()) {
        continue;
      }

      $results[] = $ref;

      if ($after) {
        if ($ref->getPanelKey() === $after->getPanelKey()) {
          $results[] = $target;
        }
      }
    }

    $this->refs = $results;

    $column_map = mgroup($results, 'getColumnKey');
    foreach ($this->columns as $column_key => $column) {
      $column->setPanelRefs(idx($column_map, $column_key, array()));
    }

    return $ref;
  }

  private function newPanelKey() {
    return Filesystem::readRandomCharacters(8);
  }


}
