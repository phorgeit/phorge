<?php

final class ShellLogView extends AphrontView {

  private $start = 1;
  private $lines;
  private $limit;
  private $highlights = array();

  public function setStart($start) {
    $this->start = $start;
    return $this;
  }

  public function setLimit($limit) {
    $this->limit = $limit;
    return $this;
  }

  public function setLines(array $lines) {
    $this->lines = $lines;
    return $this;
  }

  public function setHighlights(array $highlights) {
    $this->highlights = array_fuse($highlights);
    return $this;
  }

  public function render() {
    require_celerity_resource('phorge-source-code-view-css');
    require_celerity_resource('syntax-highlighting-css');

    Javelin::initBehavior('phorge-oncopy', array());

    $line_number = $this->start;

    $rows = array();
    foreach ($this->lines as $line) {
      $hit_limit = $this->limit &&
                   ($line_number == $this->limit) &&
                   (count($this->lines) != $this->limit);

      if ($hit_limit) {
        $content_number = '';
        $content_line = phutil_tag(
          'span',
          array(
            'class' => 'c',
          ),
          pht('...'));
      } else {
        $content_number = $line_number;
        $content_line = $line;
      }

      $row_attributes = array();
      if (isset($this->highlights[$line_number])) {
        $row_attributes['class'] = 'phorge-source-highlight';
      }

      // TODO: Provide nice links.

      $th = phutil_tag(
        'th',
        array(
          'class' => 'phorge-source-line',
        ),
        $content_number);

      $td = phutil_tag(
        'td',
        array('class' => 'phorge-source-code'),
        $content_line);

      $rows[] = phutil_tag(
        'tr',
        $row_attributes,
        array($th, $td));

      if ($hit_limit) {
        break;
      }

      $line_number++;
    }

    $classes = array();
    $classes[] = 'phorge-source-code-view';
    $classes[] = 'remarkup-code';
    $classes[] = 'PhorgeMonospaced';

    return phutil_tag(
      'div',
      array(
        'class' => 'phorge-source-code-container',
      ),
      phutil_tag(
        'table',
        array(
          'class' => implode(' ', $classes),
        ),
        phutil_implode_html('', $rows)));
  }

}
