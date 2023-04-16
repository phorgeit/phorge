<?php

final class PhorgeConfigResponse extends AphrontStandaloneHTMLResponse {

  private $view;

  public function setView(PhorgeSetupIssueView $view) {
    $this->view = $view;
    return $this;
  }

  public function getHTTPResponseCode() {
    return 500;
  }

  protected function getResources() {
    return array(
      'css/application/config/config-template.css',
      'css/application/config/setup-issue.css',
    );
  }

  protected function getResponseTitle() {
    return pht('Setup Error');
  }

  protected function getResponseBodyClass() {
    if (PhorgeSetupCheck::isInFlight()) {
      return 'setup-fatal in-flight';
    } else {
      return 'setup-fatal';
    }
  }

  protected function getResponseBody() {
    $view = $this->view;

    if (PhorgeSetupCheck::isInFlight()) {
      return $view->renderInFlight();
    } else {
      return $view->render();
    }
  }

  protected function buildPlainTextResponseString() {
    return pht(
      'This install has a fatal setup error, access the web interface '.
      'to view details and resolve it.');
  }

}
