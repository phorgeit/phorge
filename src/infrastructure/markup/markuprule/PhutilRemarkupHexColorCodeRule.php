<?php

final class PhutilRemarkupHexColorCodeRule
 extends PhabricatorRemarkupCustomInlineRule {

  public function getPriority() {
    return 1000.0;
  }

  public function apply($text) {
    // Match {#FFFFFF}
    return preg_replace_callback(
      '@\B\{(#([0-9a-fA-F]{3}){1,2})\}@',
      array($this, 'markupHexColorCodedText'),
      $text);
  }

  protected function contrastingColor($color_code) {
    $match = ltrim($color_code, '#');
    $colors_hex = str_split($match, strlen($match) / 3);
    list($r, $g, $b) = array_map('hexdec', $colors_hex);
    // Calculation adapted from Myndex, CC BY-SA 4.0
    // https://stackoverflow.com/a/69869976
    $y = pow((double)$r / 255.0, 2.2) * 0.2126 +
      pow((double)$g / 255.0, 2.2) * 0.7152 +
      pow((double)$b / 255.0, 2.2) * 0.0722;

    return ($y < 0.34) ? 'white' : 'black';
  }

  protected function markupHexColorCodedText(array $matches) {
    if ($this->getEngine()->isTextMode()) {
      $result = $matches[1];
    } else {
      if (count($matches) < 2) {
        return $matches[0];
      } else {
        $len = strlen($matches[1]);
        if (7 !== $len && 4 !== $len) {
          return $matches[0];
        }
      }
      $match = $matches[1];
      $fg = $this->contrastingColor($match);
      $result = phutil_tag(
        'tt',
        array(
          'class' => 'remarkup-monospaced',
          'style' => "color: {$fg}; background-color: {$match};",
        ),
        $match);
    }

    return $this->getEngine()->storeText($result);
  }

}
