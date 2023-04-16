<?php

final class PhorgeDashboardTextPanelType
  extends PhorgeDashboardPanelType {

  public function getPanelTypeKey() {
    return 'text';
  }

  public function getPanelTypeName() {
    return pht('Text Panel');
  }

  public function getIcon() {
    return 'fa-file-text-o';
  }

  public function getPanelTypeDescription() {
    return pht(
      'Add a text panel to the dashboard to provide instructions or '.
      'context.');
  }

  protected function newEditEngineFields(PhorgeDashboardPanel $panel) {
    return array(
      id(new PhorgeRemarkupEditField())
        ->setKey('text')
        ->setLabel(pht('Text'))
        ->setTransactionType(
          PhorgeDashboardTextPanelTextTransaction::TRANSACTIONTYPE)
        ->setValue($panel->getProperty('text', '')),
    );
  }

  public function shouldRenderAsync() {
    // Rendering text panels is normally a cheap cache hit.
    return false;
  }

  public function renderPanelContent(
    PhorgeUser $viewer,
    PhorgeDashboardPanel $panel,
    PhorgeDashboardPanelRenderingEngine $engine) {

    $text = $panel->getProperty('text', '');
    $oneoff = id(new PhorgeMarkupOneOff())->setContent($text);
    $field = 'default';

    // NOTE: We're taking extra steps here to prevent creation of a text panel
    // which embeds itself using `{Wnnn}`, recursing indefinitely.

    $parent_key = PhorgeDashboardRemarkupRule::KEY_PARENT_PANEL_PHIDS;
    $parent_phids = $engine->getParentPanelPHIDs();
    $parent_phids[] = $panel->getPHID();

    $markup_engine = id(new PhorgeMarkupEngine())
      ->setViewer($viewer)
      ->setContextObject($panel)
      ->setAuxiliaryConfig($parent_key, $parent_phids);

    $text_content = $markup_engine
      ->addObject($oneoff, $field)
      ->process()
      ->getOutput($oneoff, $field);

    return id(new PHUIPropertyListView())
      ->addTextContent($text_content);
  }

}
