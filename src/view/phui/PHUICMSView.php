<?php

final class PHUICMSView extends AphrontTagView {

  private $header;
  private $nav;
  private $crumbs;
  private $content;

  public function setHeader(PHUIHeaderView $header) {
    $this->header = $header;
    return $this;
  }

  public function setNavigation(AphrontSideNavFilterView $nav) {
    $this->nav = $nav;
    return $this;
  }

  public function setCrumbs(PHUICrumbsView $crumbs) {
    $this->crumbs = $crumbs;
    return $this;
  }

  public function setContent($content) {
    $this->content = $content;
    return $this;
  }

  protected function getTagName() {
    return 'div';
  }

  protected function getTagAttributes() {
    require_celerity_resource('phui-cms-css');

    $classes = array();
    $classes[] = 'phui-cms-view';

    return array(
        'class' => implode(' ', $classes),
      );

  }

  protected function getTagContent() {

    $content = phutil_tag(
      'div',
      array(
        'class' => 'phui-cms-page-content',
      ),
      array(
        $this->header,
        $this->content,
      ));

    $navigation = $this->nav;
    $navigation->appendChild($content);

    $page = phutil_tag(
      'div',
      array(
        'class' => 'phui-cms-inner',
      ),
      array(
        $navigation,
      ));

    $classes = array();
    $classes[] = 'phui-cms-page';

    return phutil_tag(
      'div',
      array(
        'class' => implode(' ', $classes),
      ),
      array(
        $this->crumbs,
        $page,
      ));

  }
}
