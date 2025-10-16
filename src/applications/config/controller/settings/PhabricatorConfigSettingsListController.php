<?php

final class PhabricatorConfigSettingsListController
  extends PhabricatorConfigSettingsController {

  public function handleRequest(AphrontRequest $request) {
    $viewer = $request->getViewer();

    $filter = $request->getURIData('filter');
    if (!phutil_nonempty_string($filter)) {
      $filter = 'settings';
    }

    $is_core = ($filter === 'settings');
    $is_advanced = ($filter === 'advanced');
    $is_all = ($filter === 'all');

    $show_core = ($is_core || $is_all);
    $show_advanced = ($is_advanced || $is_all);

    if ($is_core) {
      $title = pht('Core Settings');
    } else if ($is_advanced) {
      $title = pht('Advanced Settings');
    } else {
      $title = pht('All Settings');
    }

    $db_values = id(new PhabricatorConfigEntry())
      ->loadAllWhere('namespace = %s', 'default');
    $db_values = mpull($db_values, null, 'getConfigKey');

    $list = id(new PHUIObjectItemListView())
      ->setViewer($viewer)
      ->setBig(true)
      ->setFlush(true);

    $rows = array();
    $options = PhabricatorApplicationConfigOptions::loadAllOptions();
    ksort($options);

    $uninstalled_apps =
      PhabricatorApplication::getAllUninstalledApplications();
    $uninstalled_apps = mpull($uninstalled_apps, 'getName');
    $uninstalled_apps = array_map('strtolower', $uninstalled_apps);

    foreach ($options as $option) {
      $key = $option->getKey();

      $app_installed = true;
      $app_name = null;
      $pos = strpos($key, '.');
      if ($pos !== false) {
        $app_name = substr($key, 0, $pos);
      }
      if ($app_name && in_array($app_name, $uninstalled_apps)) {
        $app_installed = false;
      }

      $is_advanced = (bool)$option->getLocked();
      if ($is_advanced && !$show_advanced) {
        continue;
      }

      if (!$is_advanced && !$show_core) {
        continue;
      }

      $db_value = idx($db_values, $key);

      $item = $this->newConfigOptionView($option, $db_value, $app_installed);
      $list->addItem($item);
    }

    $header = $this->buildHeaderView($title);

    $crumbs = $this->newCrumbs()
      ->addTextCrumb($title);

    $content = id(new PHUITwoColumnView())
      ->setHeader($header)
      ->setFooter($list);

    $nav = $this->newNavigation($filter);

    return $this->newPage()
      ->setTitle($title)
      ->setCrumbs($crumbs)
      ->setNavigation($nav)
      ->appendChild($content);
  }

  /**
   * @return PHUIObjectItemView
   */
  private function newConfigOptionView(
    PhabricatorConfigOption $option,
    ?PhabricatorConfigEntry $stored_value = null,
    bool $app_installed = true) {

    $summary = $option->getSummary();

    $stack = PhabricatorEnv::getConfigSourceStack();
    $stack = $stack->getStack();
    $key = $option->getKey();
    $byline = null;
    foreach ($stack as $source) {
      $value = $source->getKeys(array($key));
      if ($value) {
        $byline = $source->getName();
        break;
      }
    }

    $item = id(new PHUIObjectItemView())
      ->setHeader($option->getKey())
      ->setClickable(true)
      ->addByline($byline)
      ->setHref('/config/edit/'.$option->getKey().'/')
      ->addAttribute($summary);

    $color = null;
    if ($stored_value && !$stored_value->getIsDeleted()) {
      $item->setEffect('visited');
      $color = 'violet';
    }

    if (!$app_installed) {
      $item->setDisabled(true)
            ->setStatusIcon(
              'fa-times-circle grey',
              pht('Disabled Application'));
    } else if ($option->getHidden()) {
      $item->setStatusIcon('fa-eye-slash', pht('Hidden'));
    } else if ($option->getLocked()) {
      $item->setStatusIcon('fa-lock '.$color, pht('Locked'));
    } else if ($color) {
      $item->setStatusIcon('fa-pencil '.$color, pht('Editable'));
    } else {
      $item->setStatusIcon('fa-circle-o grey', pht('Default'));
    }

    return $item;
  }

}
