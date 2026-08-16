<?php

final class DivinerSectionView extends AphrontTagView {

  private $header;
  private $content;
  private $anchor;

  public function addContent($content) {
    $this->content[] = $content;
    return $this;
  }

  public function setHeader($text) {
    $this->header = $text;
    return $this;
  }

  public function setAnchor(PhabricatorAnchorView $anchor) {
    $this->anchor = $anchor;
    return $this;
  }


  protected function getTagName() {
    return 'div';
  }

  protected function getTagAttributes() {
    return array(
      'class' => 'diviner-document-section',
    );
  }

  protected function getTagContent() {
    require_celerity_resource('diviner-shared-css');

    $header = id(new PHUIHeaderView())
      ->setBleedHeader(true)
      ->addClass('diviner-section-header')
      ->setHeader($this->header);

    $content = phutil_tag_div('diviner-section-content', $this->content);

    return array(
      $this->anchor,
      $header,
      $content,
    );
  }

}
