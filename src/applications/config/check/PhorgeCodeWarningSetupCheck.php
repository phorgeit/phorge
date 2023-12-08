<?php

final class PhorgeCodeWarningSetupCheck extends PhabricatorSetupCheck {

  public function getExecutionOrder() {
    return 2000;
  }

  public function getDefaultGroup() {
    return self::GROUP_OTHER;
  }

  protected function executeChecks() {
    $warnings = (new PhorgeSystemDeprecationWarningListener())->getWarnings();
    if (!$warnings) {
      return;
    }

    $link = phutil_tag(
      'a',
      array('href' => 'https://we.phorge.it/w/docs/report-warnings/'),
      pht('%s\'s home page', PlatformSymbols::getPlatformServerName()));

    $message = pht(
      'There is some deprecated code found in the %s code-base.'.
      "\n\n".
      "This isn't a problem yet, but it means that %s might stop working if ".
      'you upgrade PHP version.'.
      "\n\n".
      'This page records a sample of the cases since last server restart. '.
      "\n\n".
      'To solve this issue, either:'.
      "\n\n".
      '- Visit %s, file bug report with the information below, or'.
      "\n".
      '- Ignore this issue using the `Ignore` button below.'.
      "\n\n",
      PlatformSymbols::getPlatformServerName(),
      PlatformSymbols::getPlatformServerName(),
      $link);
    $message = array($message);

    $message[] = pht('PHP version: %s', phpversion());
    $message[] = "\n\n";

    $message[] = pht('Recorded items (sample):');
    $list = array();
    $warnings = array_reverse(isort($warnings, 'counter'));
    foreach ($warnings as $key => $data) {
      $summary = pht(
        '%s, occurrences: %s',
        $key,
        $data['counter']);

      $trace = phutil_tag('tt', array(),
      array($data['message'] , "\n", $data['trace']));

      $list[] = phutil_tag(
        'li',
        array(),
        phutil_tag(
          'details',
          array(),
          array(
            phutil_tag('summary', array(), $summary),
            $trace,
      )));
    }
    $message[] = phutil_tag('ul', array(), $list);


    $this->newIssue('deprecations')
      ->setName(pht('Deprecated Code'))
      ->setMessage($message)
      ->setSummary(pht('There is some deprecated code found in the code-base.'))
      ->addLink(
       'https://we.phorge.it/w/docs/report-warnings/',
       'More Details on the website');
  }

}
