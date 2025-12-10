<?php

abstract class AphrontHTMLResponse extends AphrontResponse {

  public function getHeaders() {
    $headers = array(
      array('Content-Type', 'text/html; charset=UTF-8'),
    );
    try {
      $cdn = PhabricatorEnv::getEnvConfig('security.alternate-file-domain');
    } catch (Throwable $ex) {
      $cdn = null;
    }
    if ($cdn) {
      $headers[] = array('Link', '<'.$cdn.'>; rel="preconnect"');
    }

    $headers = array_merge(parent::getHeaders(), $headers);
    return $headers;
  }

}
