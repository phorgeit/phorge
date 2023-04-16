<?php

final class PhorgeCountdownView extends AphrontView {

  private $countdown;

  public function setCountdown(PhorgeCountdown $countdown) {
    $this->countdown = $countdown;
    return $this;
  }

  public function render() {
    $countdown = $this->countdown;
    require_celerity_resource('phorge-countdown-css');

    $header_text = array(
      $countdown->getMonogram(),
      ' ',
      phutil_tag(
        'a',
        array(
          'href' => $countdown->getURI(),
        ),
        $countdown->getTitle()),
    );

    $header = id(new PHUIHeaderView())
      ->setHeader($header_text);

    $ths = array(
      phutil_tag('th', array(), pht('Days')),
      phutil_tag('th', array(), pht('Hours')),
      phutil_tag('th', array(), pht('Minutes')),
      phutil_tag('th', array(), pht('Seconds')),
    );

    $dashes = array(
      javelin_tag('td', array('sigil' => 'phorge-timer-days'), '-'),
      javelin_tag('td', array('sigil' => 'phorge-timer-hours'), '-'),
      javelin_tag('td', array('sigil' => 'phorge-timer-minutes'), '-'),
      javelin_tag('td', array('sigil' => 'phorge-timer-seconds'), '-'),
    );

    $epoch = $countdown->getEpoch();
    $launch_date = phorge_datetime($epoch, $this->getUser());
    $foot = phutil_tag(
      'td',
      array(
        'colspan' => '4',
        'class' => 'phorge-timer-foot',
      ),
      $launch_date);

    $description = $countdown->getDescription();
    if (strlen($description)) {
      $description = new PHUIRemarkupView($this->getUser(), $description);
      $description = phutil_tag(
        'div',
        array(
          'class' => 'countdown-description phorge-remarkup',
        ),
        $description);
    }

    $container = celerity_generate_unique_node_id();
    $content = phutil_tag(
      'div',
      array('class' => 'phorge-timer', 'id' => $container),
      array(
        $description,
        phutil_tag('table', array('class' => 'phorge-timer-table'), array(
          phutil_tag('tr', array(), $ths),
          phutil_tag('tr', array(), $dashes),
          phutil_tag('tr', array(), $foot),
        )),
      ));

    Javelin::initBehavior('countdown-timer', array(
      'timestamp' => $countdown->getEpoch(),
      'container' => $container,
    ));

    return id(new PHUIObjectBoxView())
      ->setHeader($header)
      ->setBackground(PHUIObjectBoxView::BLUE_PROPERTY)
      ->addClass('phorge-timer-view')
      ->appendChild($content);
  }

}
