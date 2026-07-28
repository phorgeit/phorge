<?php

final class PhabricatorBritishEnglishTranslation
  extends PhutilTranslation {

  public function getLocaleCode() {
    return 'en_GB';
  }

  protected function getTranslations() {
    return array(
      "%s set this project's color to %s." =>
        "%s set this project's colour to %s.",
      'Choose Icon and Color...' =>
        'Choose Icon and Colour...',
      'Choose Background Color' =>
        'Choose Background Colour',
      'Color' => 'Colour',
      'Colors' => 'Colours',
      'Colors and Transforms' => 'Colours and Transforms',
      'Configure the UI, including colors.' =>
        'Configure the UI, including colours.',
      'Flag Color' => 'Flag Colour',
      'Sets the default color scheme.' =>
        'Sets the default colour scheme.',
    );
  }

}
