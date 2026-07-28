<?php

final class PhorgeConfigOptionListView extends AphrontView {

  private $options;
  private $flush;
  private $appKey;


  public function setOptions(array $options) {
    assert_instances_of($options, PhabricatorConfigOption::class);
    $this->options = $options;
    return $this;
  }

  public function setFlush($flush) {
    $this->flush = $flush;
    return $this;
  }

  public function setAppKey($app_key) {
    $this->appKey = $app_key;
    return $this;
  }

  public function render() {

    $list = id(new PHUIObjectItemListView())
      ->setViewer($this->getViewer())
      ->setBig(true)
      ->setFlush($this->flush)
      ->setNoDataString(pht('No settings available.'));

    foreach ($this->options as $option) {
      $item = $this->newConfigOptionView($option);
      $list->addItem($item);
    }

    return $list;
  }

  /**
   * @return PHUIObjectItemView
   */
  private function newConfigOptionView(
    PhabricatorConfigOption $option) {

    $summary = $option->getSummary();

    $stack = PhabricatorEnv::getConfigSourceStack();
    $stack = $stack->getStack();
    $key = $option->getKey();
    $byline = null;
    $have_db_value = false;
    foreach ($stack as $source) {
      $value = $source->getKeys(array($key));
      if ($value) {
        $byline = $source->getName();
        if ($source instanceof PhabricatorConfigDatabaseSource) {
          $have_db_value = true;
        }
        break;
      }
    }

    $application_class = $option->getGroup()->getApplicationClassName();
    $application = PhabricatorApplication::getByClass($application_class);
    $app_installed = $application->isInstalled();

    $edit_uri = new PhutilURI('/config/edit/'.$option->getKey().'/');
    if ($this->appKey) {
      $edit_uri->appendQueryParam('application', $this->appKey);
    }


    $item = id(new PHUIObjectItemView())
      ->setHeader($option->getKey())
      ->setClickable(true)
      ->addByline($byline)
      ->addByline($application->getName())
      ->setHref($edit_uri)
      ->addAttribute($summary);

    $color = null;
    if ($have_db_value) {
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
