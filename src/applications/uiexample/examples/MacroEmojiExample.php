<?php

final class MacroEmojiExample extends PhabricatorUIExample {

  public function getName() {
    return pht('Emoji');
  }

  public function getDescription() {
    return pht('Shiny happy people holding hands.');
  }

  public function getCategory() {
    return pht('Catalogs');
  }

  public function renderExample() {

    $raw = id(new PhabricatorEmojiRemarkupRule())
      ->markupEmojiJSON();

    $json = phutil_json_decode($raw);

    $ficons = array();
    foreach ($json as $shortname => $hex) {
      $ficons[] = id(new PHUIIconView())
        ->addClass('phui-example-icon-name')
        ->setText($hex.' '.$shortname);
    }

    $content = id(new PHUIBoxView())
      ->appendChild($ficons)
      ->addMargin(PHUI::MARGIN_LARGE);


    $wrap = id(new PHUIObjectBoxView())
      ->setHeaderText(pht('Emojis'))
      ->appendChild($content);

    return phutil_tag(
      'div',
        array(
          'class' => 'phui-icon-example',
        ),
        array(
          $wrap,
        ));
      }
}
