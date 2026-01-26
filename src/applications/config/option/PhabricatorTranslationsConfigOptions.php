<?php

final class PhabricatorTranslationsConfigOptions
  extends PhabricatorApplicationConfigOptions {

  public function getName() {
    return pht('Translations');
  }

  public function getDescription() {
    return pht('Options relating to translations.');
  }

  public function getIcon() {
    return 'fa-globe';
  }

  public function getGroup() {
    return 'core';
  }

  public function getOptions() {
    return array(
      $this->newOption('translation.override', 'wild', array())
        ->setSummary(pht('Override translations.'))
        ->setDescription(
          pht(
            "You can use '%s' if you don't want to create a full translation ".
            "to give users an option for switching to it and you just want to ".
            "override some strings in the default translation.",
            'translation.override'))
        ->addExample(
          '{"some string": "my alternative"}',
          pht('Valid Setting')),
      // Ideally this would be an enum but it can't be because of bootstrapping
      // problems - this code runs before extensions load, so if we load
      // locales now to populate the enum then locales defined by extensions
      // wouldn't load.
      $this->newOption('locale.command', 'string', 'en_US')
        ->setSummary(pht('Locale code of command-line locale.'))
        ->setDescription(pht(
          'What locale to use for command-line scripts that '.
          'don\'t specify a `%s` argument.',
          '--locale')),
    );
  }

}
