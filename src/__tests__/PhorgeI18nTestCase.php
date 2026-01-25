<?php

final class PhorgeI18nTestCase extends PhabricatorTestCase {
    public function testi18nValidation() {
      $validator = new PhorgeInternationalizationValidator();
      $errors = $validator->validateLibraries(
        $validator->loadExtractions(true, true));
      $this->assertEqual(array(),
        $errors,
        pht('i18n validation errors found!'));
    }
}
