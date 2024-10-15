<?php

final class PhabricatorXHPASTViewFrameController
  extends PhabricatorXHPASTViewController {

  public function shouldAllowPublic() {
    return true;
  }

  public function handleRequest(AphrontRequest $request) {
    $id = $request->getURIData('id');

    return $this->newPage()
     ->setApplicationName('XHPASTView')
     ->setBaseURI('/xhpast/')
     ->setTitle(pht('XHPAST View'))
     ->setGlyph("\xE2\x96\xA0")
     ->appendChild(phutil_tag(
        'iframe',
        array(
          'src'         => "/xhpast/frameset/{$id}/",
          'frameborder' => '0',
          'style'       => 'width: 100%; height: 800px;',
        '',
      )));
  }
}
