<?php

final class PhorgePHPASTViewStreamController
  extends PhorgePHPASTViewPanelController {

  public function handleRequest(AphrontRequest $request) {
    $storage = $this->getStorageTree();
    $err = $storage->getError();
    $token_stream = $storage->getTokenStream();

    if ($err) {
      return $this->buildPHPASTViewPanelResponse($err);
    }

    $tokens = array();
    foreach ($token_stream as $id => $token) {
      $seq = $id;
      $name = $token->getTokenName();
      $title = pht('Token %d: %s', $seq, $name);

      $tokens[] = phutil_tag(
        'span',
        array(
          'title' => $title,
          'class' => 'token',
        ),
        $token->text);
    }

    return $this->buildPHPASTViewPanelResponse(
      phutil_implode_html('', $tokens));
  }
}
