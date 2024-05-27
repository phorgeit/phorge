<?php

final class PHUIBoxView extends AphrontTagView {

  private $margin = array();
  private $padding = array();
  private $border = false;
  private $color;
  private $collapsible;

  const BLUE = 'phui-box-blue';
  const GREY = 'phui-box-grey';

  public function addMargin($margin) {
    $this->margin[] = $margin;
    return $this;
  }

  public function addPadding($padding) {
    $this->padding[] = $padding;
    return $this;
  }

  public function setBorder($border) {
    $this->border = $border;
    return $this;
  }

  public function setColor($color) {
    $this->color = $color;
    return $this;
  }

  /**
   * Render PHUIBoxView as a <details> instead of a <div> HTML tag.
   * To be used for collapse/expand in combination with PHUIHeaderView.
   *
   * @param bool True to wrap in <summary> instead of <div> HTML tag.
   */
  public function setCollapsible($collapsible) {
    $this->collapsible = $collapsible;
    return $this;
  }

  protected function getTagAttributes() {
    require_celerity_resource('phui-box-css');
    $outer_classes = array();
    $outer_classes[] = 'phui-box';

    if ($this->border) {
      $outer_classes[] = 'phui-box-border';
    }

    foreach ($this->margin as $margin) {
      $outer_classes[] = $margin;
    }

    foreach ($this->padding as $padding) {
      $outer_classes[] = $padding;
    }

    if ($this->color) {
      $outer_classes[] = $this->color;
    }

    $tag_classes = array('class' => $outer_classes);

    if ($this->collapsible) {
      $attribute = array('open' => ''); // expand column by default
      $tag_classes = array_merge($tag_classes, $attribute);
    }

    return $tag_classes;
  }

  protected function getTagName() {
    if ($this->collapsible) {
      return 'details';
    }
    return 'div';
  }

  protected function getTagContent() {
    return $this->renderChildren();
  }
}
