<?php

final class PHUIObjectItemView extends AphrontTagView {

  private $objectName;
  private $header;
  private $subhead;
  private $href;
  private $attributes = array();
  private $icons = array();
  private $barColor;
  private $object;
  private $effect;
  private $statusIcon;
  private $handleIcons = array();
  private $bylines = array();
  private $grippable;
  private $actions = array();
  private $headIcons = array();
  private $disabled;
  private $imageURI;
  private $imageHref;
  private $imageIcon;
  private $titleText;
  private $badge;
  private $countdownNum;
  private $countdownNoun;
  private $sideColumn;
  private $coverImage;
  private $description;
  private $clickable;
  private $mapViews = array();
  private $menu;

  private $selectableName;
  private $selectableValue;
  private $isSelected;
  private $isForbidden;

  public function setDisabled($disabled) {
    $this->disabled = $disabled;
    return $this;
  }

  public function getDisabled() {
    return $this->disabled;
  }

  public function addHeadIcon($icon) {
    $this->headIcons[] = $icon;
    return $this;
  }

  public function setObjectName($name) {
    $this->objectName = $name;
    return $this;
  }

  public function setGrippable($grippable) {
    $this->grippable = $grippable;
    return $this;
  }

  public function getGrippable() {
    return $this->grippable;
  }

  public function setEffect($effect) {
    $this->effect = $effect;
    return $this;
  }

  public function getEffect() {
    return $this->effect;
  }

  public function setObject($object) {
    $this->object = $object;
    return $this;
  }

  public function getObject() {
    return $this->object;
  }

  /**
   * Set the href attribute
   *
   * @param string|PhutilURI|null $href
   * @return self
   */
  public function setHref($href) {
    PhutilURI::checkHrefType($href);

    $this->href = $href;
    return $this;
  }

  /**
   * Get the href attribute
   *
   * @see PHUIObjectItemView::setHref()
   * @return string|PhutilURI|null
   */
  public function getHref() {
    return $this->href;
  }

  public function setHeader($header) {
    $this->header = $header;
    return $this;
  }

  public function setSubHead($subhead) {
    $this->subhead = $subhead;
    return $this;
  }

  public function setBadge(PHUIBadgeMiniView $badge) {
    $this->badge = $badge;
    return $this;
  }

  public function setCountdown($num, $noun) {
    $this->countdownNum = $num;
    $this->countdownNoun = $noun;
    return $this;
  }

  public function setTitleText($title_text) {
    $this->titleText = $title_text;
    return $this;
  }

  public function getTitleText() {
    return $this->titleText;
  }

  public function getHeader() {
    return $this->header;
  }

  public function addByline($byline) {
    $this->bylines[] = $byline;
    return $this;
  }

  public function setImageURI($image_uri) {
    $this->imageURI = $image_uri;
    return $this;
  }

  /**
   * Set the image href attribute
   *
   * @param string|PhutilURI|null $image_href
   * @return self
   */
  public function setImageHref($image_href) {
    PhutilURI::checkHrefType($image_href);

    $this->imageHref = $image_href;
    return $this;
  }

  public function getImageURI() {
    return $this->imageURI;
  }

  public function setImageIcon($image_icon) {
    if (!$image_icon instanceof PHUIIconView) {
      $image_icon = id(new PHUIIconView())
        ->setIcon($image_icon);
    }
    $this->imageIcon = $image_icon;
    return $this;
  }

  public function getImageIcon() {
    return $this->imageIcon;
  }

  public function setCoverImage($image) {
    $this->coverImage = $image;
    return $this;
  }

  public function setDescription($description) {
    $this->description = $description;
    return $this;
  }

  public function setSelectable(
    $name,
    $value,
    $is_selected,
    $is_forbidden = false) {

    $this->selectableName = $name;
    $this->selectableValue = $value;
    $this->isSelected = $is_selected;
    $this->isForbidden = $is_forbidden;

    return $this;
  }

  public function setClickable($clickable) {
    $this->clickable = $clickable;
    return $this;
  }

  public function getClickable() {
    return $this->clickable;
  }

  public function setEpoch($epoch) {
    $date = vixon_dual_datetime($epoch, $this->getUser());
    $this->addIcon('none', $date);
    return $this;
  }

  public function addAction(PHUIListItemView $action) {
    if (count($this->actions) >= 3) {
      throw new Exception(pht('Limit 3 actions per item.'));
    }
    $this->actions[] = $action;
    return $this;
  }

  public function addIcon($icon, $label = null, $attributes = array()) {
    $this->icons[] = array(
      'icon'  => $icon,
      'label' => $label,
      'attributes' => $attributes,
    );
    return $this;
  }

  public function newMenuItem() {
    if (!$this->menu) {
      $this->menu = new FuelMenuView();
    }

    return $this->menu->newItem();
  }

  public function newMapView() {
    $list = id(new FuelMapView())
      ->addClass('fuel-map-property-list');
    $this->mapViews[] = $list;
    return $list;
  }

  /**
   * This method has been deprecated, use @{method:setImageIcon} instead.
   *
   * @deprecated
   */
  public function setIcon($icon) {
    phlog(
      pht('Deprecated call to setIcon(), use setImageIcon() instead.'));

    return $this->setImageIcon($icon);
  }

  public function setStatusIcon($icon, $label = null) {
    $this->statusIcon = array(
      'icon' => $icon,
      'label' => $label,
    );
    return $this;
  }

  public function addHandleIcon(
    PhabricatorObjectHandle $handle,
    $label = null) {
    $this->handleIcons[] = array(
      'icon' => $handle,
      'label' => $label,
    );
    return $this;
  }

  public function setBarColor($bar_color) {
    $this->barColor = $bar_color;
    return $this;
  }

  public function getBarColor() {
    return $this->barColor;
  }

  public function addAttribute($attribute) {
    if (!empty($attribute)) {
      $this->attributes[] = $attribute;
    }
    return $this;
  }

  public function setSideColumn($column) {
    $this->sideColumn = $column;
    return $this;
  }

  protected function getTagName() {
    return 'li';
  }

  protected function getTagAttributes() {
    $sigils = array();

    $item_classes = array();
    $item_classes[] = 'phui-oi';

    if ($this->icons) {
      $item_classes[] = 'phui-oi-with-icons';
    }

    if ($this->attributes) {
      $item_classes[] = 'phui-oi-with-attrs';
    }

    if ($this->handleIcons) {
      $item_classes[] = 'phui-oi-with-handle-icons';
    }

    if ($this->barColor) {
      $item_classes[] = 'phui-oi-bar-color-'.$this->barColor;
    } else {
      $item_classes[] = 'phui-oi-no-bar';
    }

    if ($this->actions) {
      $n = count($this->actions);
      $item_classes[] = 'phui-oi-with-actions';
      $item_classes[] = 'phui-oi-with-'.$n.'-actions';
    }

    if ($this->disabled) {
      $item_classes[] = 'phui-oi-disabled';
    } else {
      $item_classes[] = 'phui-oi-enabled';
    }

    switch ($this->effect) {
      case 'highlighted':
        $item_classes[] = 'phui-oi-highlighted';
        break;
      case 'selected':
        $item_classes[] = 'phui-oi-selected';
        break;
      case 'visited':
        $item_classes[] = 'phui-oi-visited';
        break;
      case null:
        break;
      default:
        throw new Exception(pht('Invalid effect!'));
    }

    if ($this->isForbidden) {
      $item_classes[] = 'phui-oi-forbidden';
    } else if ($this->isSelected) {
      $item_classes[] = 'phui-oi-selected';
    }

    if ($this->selectableName !== null && !$this->isForbidden) {
      $item_classes[] = 'phui-oi-selectable';
      $sigils[] = 'phui-oi-selectable';

      Javelin::initBehavior('phui-selectable-list');
    }

    $is_grippable = $this->getGrippable();
    if ($is_grippable !== null) {
      $item_classes[] = 'phui-oi-has-grip';
      if ($is_grippable) {
        $item_classes[] = 'phui-oi-grippable';
      } else {
        $item_classes[] = 'phui-oi-ungrippable';
      }
    }

    if ($this->getImageURI()) {
      $item_classes[] = 'phui-oi-with-image';
    }

    if ($this->getImageIcon()) {
      $item_classes[] = 'phui-oi-with-image-icon';
    }

    if ($this->getClickable()) {
      Javelin::initBehavior('linked-container');

      $item_classes[] = 'phui-oi-linked-container';
      $sigils[] = 'linked-container';
    }

    return array(
      'class' => $item_classes,
      'sigil' => $sigils,
    );
  }

  protected function getTagContent() {
    $viewer = $this->getUser();

    $content_classes = array();
    $content_classes[] = 'phui-oi-content';

    $header_name = array();

    if ($viewer) {
      $header_name[] = id(new PHUISpacesNamespaceContextView())
        ->setViewer($viewer)
        ->setObject($this->object);
    }

    if ($this->objectName) {
      $header_name[] = array(
        javelin_tag(
          'span',
          array(
            'class' => 'phui-oi-objname',
            'sigil' => 'ungrabbable',
          ),
          $this->objectName),
        ' ',
      );
    }

    $title_text = null;
    if ($this->titleText) {
      $title_text = $this->titleText;
    } else if ($this->href) {
      $title_text = $this->header;
    }

    $header_link = phutil_tag(
      $this->href ? 'a' : 'div',
      array(
        'href' => $this->href,
        'class' => 'phui-oi-link',
        'title' => $title_text,
      ),
      $this->header);

    $description_tag = null;
    if ($this->description) {
      $decription_id = celerity_generate_unique_node_id();
      $description_tag = id(new PHUITagView())
        ->setIcon('fa-ellipsis-h')
        ->addClass('phui-oi-description-tag')
        ->setType(PHUITagView::TYPE_SHADE)
        ->setColor(PHUITagView::COLOR_GREY)
        ->addSigil('jx-toggle-class')
        ->setSlimShady(true)
        ->setMetaData(array(
          'map' => array(
            $decription_id => 'phui-oi-description-reveal',
          ),
        ));
    }

    $header = phutil_tag(
      'div',
      array(
        'class' => 'phui-oi-name',
      ),
      array(
        $this->headIcons,
        $header_name,
        $header_link,
        $description_tag,
      ));

    $icons = array();
    if ($this->icons) {
      $icon_list = array();
      foreach ($this->icons as $spec) {
        $icon = $spec['icon'];
        $icon = id(new PHUIIconView())
          ->setIcon($icon)
          ->addClass('phui-oi-icon-image');

        if (isset($spec['attributes']['tip'])) {
          $sigil = 'has-tooltip';
          $meta = array(
            'tip' => $spec['attributes']['tip'],
            'align' => 'W',
          );
          $icon->addSigil($sigil);
          $icon->setMetadata($meta);
        }

        $label = phutil_tag(
          'span',
          array(
            'class' => 'phui-oi-icon-label',
          ),
          $spec['label']);

        $classes = array();
        $classes[] = 'phui-oi-icon';
        if (isset($spec['attributes']['class'])) {
          $classes[] = $spec['attributes']['class'];
        }

        $icon_list[] = javelin_tag(
          'li',
          array(
            'class' => implode(' ', $classes),
          ),
          array(
            $icon,
            $label,
          ));
      }

      $icons[] = phutil_tag(
        'ul',
        array(
          'class' => 'phui-oi-icons',
        ),
        $icon_list);
    }

    $handle_bar = null;
    if ($this->handleIcons) {
      $handle_bar = array();
      foreach ($this->handleIcons as $handleicon) {
        $handle_bar[] =
          $this->renderHandleIcon($handleicon['icon'], $handleicon['label']);
      }
      $handle_bar = phutil_tag(
        'li',
        array(
          'class' => 'phui-oi-handle-icons',
        ),
        $handle_bar);
    }

    $bylines = array();
    if ($this->bylines) {
      foreach ($this->bylines as $byline) {
        $bylines[] = phutil_tag(
          'div',
          array(
            'class' => 'phui-oi-byline',
          ),
          $byline);
      }
      $bylines = phutil_tag(
        'div',
        array(
          'class' => 'phui-oi-bylines',
        ),
        $bylines);
    }

    $subhead = null;
    if ($this->subhead) {
      $subhead = phutil_tag(
        'div',
        array(
          'class' => 'phui-oi-subhead',
        ),
        $this->subhead);
    }

    if ($this->description) {
      $subhead = phutil_tag(
        'div',
        array(
          'class' => 'phui-oi-subhead phui-oi-description',
          'id' => $decription_id,
        ),
        $this->description);
    }

    if ($icons) {
      $icons = phutil_tag(
        'div',
        array(
          'class' => 'phui-object-icon-pane',
        ),
        $icons);
    }

    $attrs = null;
    if ($this->attributes || $handle_bar) {
      $attrs = array();
      $spacer = phutil_tag(
        'span',
        array(
          'class' => 'phui-oi-attribute-spacer',
        ),
        "\xC2\xB7");
      $first = true;
      foreach ($this->attributes as $attribute) {
        $attrs[] = phutil_tag(
          'li',
          array(
            'class' => 'phui-oi-attribute',
          ),
          array(
            ($first ? null : $spacer),
            $attribute,
          ));
        $first = false;
      }

      $attrs = phutil_tag(
        'ul',
        array(
          'class' => 'phui-oi-attributes',
        ),
        array(
          $handle_bar,
          $attrs,
        ));
    }

    $status = null;
    if ($this->statusIcon) {
      $icon = $this->statusIcon;
      $status = $this->renderStatusIcon($icon['icon'], $icon['label']);
    }

    $grippable = null;
    if ($this->getGrippable() !== null) {
      $grippable = phutil_tag(
        'div',
        array(
          'class' => 'phui-oi-grip',
        ),
        '');
    }

    $map_views = null;
    if ($this->mapViews) {
      $grid = id(new FuelGridView())
        ->addClass('fuel-grid-property-list');

      $row = $grid->newRow();
      foreach ($this->mapViews as $map_view) {
        $row->newCell()
          ->setContent($map_view);
      }

      $map_views = $grid;
    }

    $content = phutil_tag(
      'div',
      array(
        'class' => implode(' ', $content_classes),
      ),
      array(
        $subhead,
        $attrs,
        $map_views,
        $this->renderChildren(),
      ));

    $image = null;
    if ($this->getImageURI()) {
      $image = phutil_tag(
        'div',
        array(
          'class' => 'phui-oi-image',
          'style' => 'background-image: url('.$this->getImageURI().')',
        ),
        '');
    } else if ($this->getImageIcon()) {
      $image = phutil_tag(
        'div',
        array(
          'class' => 'phui-oi-image-icon',
        ),
        $this->getImageIcon());
    }

    if ($image && (phutil_nonempty_stringlike($this->href) ||
        phutil_nonempty_stringlike($this->imageHref))) {
      $image_href = ($this->imageHref) ? $this->imageHref : $this->href;
      $image = phutil_tag(
        'a',
        array(
          'href' => $image_href,
        ),
        $image);
    }

    /* Build a fake table */
    $column0 = null;
    if ($status) {
      $column0 = phutil_tag(
        'div',
        array(
          'class' => 'phui-oi-col0',
        ),
        $status);
    }

    if ($this->badge) {
      $column0 = phutil_tag(
        'div',
        array(
          'class' => 'phui-oi-col0 phui-oi-badge',
        ),
        $this->badge);
    }

    if ($this->countdownNum) {
      $countdown = phutil_tag(
        'div',
        array(
          'class' => 'phui-oi-countdown-number',
        ),
        array(
          phutil_tag_div('', $this->countdownNum),
          phutil_tag_div('', $this->countdownNoun),
        ));
      $column0 = phutil_tag(
        'div',
        array(
          'class' => 'phui-oi-col0 phui-oi-countdown',
        ),
        $countdown);
    }

    if ($this->selectableName !== null) {
      if (!$this->isForbidden) {
        $checkbox = phutil_tag(
          'input',
          array(
            'type' => 'checkbox',
            'name' => $this->selectableName,
            'value' => $this->selectableValue,
            'checked' => ($this->isSelected ? 'checked' : null),
          ));
      } else {
        $checkbox = null;
      }

      $column0 = phutil_tag(
        'div',
        array(
          'class' => 'phui-oi-col0 phui-oi-checkbox',
        ),
        $checkbox);
    }

    $column1 = phutil_tag(
      'div',
      array(
        'class' => 'phui-oi-col1',
      ),
      array(
        $header,
        $content,
      ));

    $column2 = null;
    if ($icons || $bylines) {
      $column2 = phutil_tag(
        'div',
        array(
          'class' => 'phui-oi-col2',
        ),
        array(
          $icons,
          $bylines,
        ));
    }

    /* Fixed width, right column container. */
    $column3 = null;
    if ($this->sideColumn) {
      $column3 = phutil_tag(
        'div',
        array(
          'class' => 'phui-oi-col2 phui-oi-side-column',
        ),
        array(
          $this->sideColumn,
        ));
    }

    $table = phutil_tag(
      'div',
      array(
        'class' => 'phui-oi-table',
      ),
      phutil_tag_div(
        'phui-oi-table-row',
        array(
          $column0,
          $column1,
          $column2,
          $column3,
        )));

    $box = phutil_tag(
      'div',
      array(
        'class' => 'phui-oi-content-box',
      ),
      array(
        $grippable,
        $table,
      ));

    $actions = array();
    if ($this->actions) {
      Javelin::initBehavior('phabricator-tooltips');

      foreach (array_reverse($this->actions) as $action) {
        $action->setRenderNameAsTooltip(true);
        $actions[] = $action;
      }
      $actions = phutil_tag(
        'ul',
        array(
          'class' => 'phui-oi-actions',
        ),
        $actions);
    }

    $frame_content = phutil_tag(
      'div',
      array(
        'class' => 'phui-oi-frame-content',
      ),
      array(
        $actions,
        $image,
        $box,
      ));

    if ($this->menu) {
      $grid_view = id(new FuelGridView())
        ->addClass('fuel-grid-tablet');
      $grid_row = $grid_view->newRow();

      $grid_row->newCell()
        ->setContent($frame_content);

      $menu = $this->menu;

      $grid_row->newCell()
        ->addClass('phui-oi-menu')
        ->setContent($menu);

      $frame_content = $grid_view;
    }

    $frame_cover = null;
    if ($this->coverImage) {
      $cover_image = phutil_tag(
        'img',
        array(
          'src' => $this->coverImage,
          'class' => 'phui-oi-cover-image',
        ));

      $frame_cover = phutil_tag(
        'div',
        array(
          'class' => 'phui-oi-frame-cover',
        ),
        $cover_image);
    }

    $frame = phutil_tag(
      'div',
      array(
        'class' => 'phui-oi-frame',
      ),
      array(
        $frame_cover,
        $frame_content,
      ));

    return $frame;
  }

  private function renderStatusIcon($icon, $label) {
    Javelin::initBehavior('phabricator-tooltips');

    $icon = id(new PHUIIconView())
      ->setIcon($icon);

    $options = array(
      'class' => 'phui-oi-status-icon',
    );

    if (phutil_nonempty_string($label)) {
      $options['sigil'] = 'has-tooltip';
      $options['meta']  = array('tip' => $label, 'size' => 300);
    }

    return javelin_tag('div', $options, $icon);
  }


  private function renderHandleIcon(PhabricatorObjectHandle $handle, $label) {
    Javelin::initBehavior('phabricator-tooltips');

    $options = array(
      'class' => 'phui-oi-handle-icon',
      'style' => 'background-image: url('.$handle->getImageURI().')',
    );

    if (strlen($label)) {
      $options['sigil'] = 'has-tooltip';
      $options['meta']  = array('tip' => $label, 'align' => 'E');
    }

    return javelin_tag('span', $options, '');
  }

}
