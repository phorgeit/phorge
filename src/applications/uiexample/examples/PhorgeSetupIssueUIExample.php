<?php

final class PhorgeSetupIssueUIExample extends PhorgeUIExample {

  public function getName() {
    return pht('Setup Issue');
  }

  public function getDescription() {
    return pht('Setup errors and warnings.');
  }

  public function getCategory() {
    return pht('Single Use');
  }

  public function renderExample() {
    $request = $this->getRequest();
    $user = $request->getUser();

    $issue = id(new PhorgeSetupIssue())
      ->setShortName(pht('Short Name'))
      ->setName(pht('Name'))
      ->setSummary(pht('Summary'))
      ->setMessage(pht('Message'))
      ->setIssueKey('example.key')
      ->addCommand('$ # Add Command')
      ->addCommand(hsprintf('<tt>$</tt> %s', '$ ls -1 > /dev/null'))
      ->addPHPConfig('php.config.example')
      ->addPhorgeConfig('test.value')
      ->addPHPExtension('libexample');

    // NOTE: Since setup issues may be rendered before we can build the page
    // chrome, they don't explicitly include resources.
    require_celerity_resource('setup-issue-css');

    $view = id(new PhorgeSetupIssueView())
      ->setIssue($issue);

    return $view;
  }
}
