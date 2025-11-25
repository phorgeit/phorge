<?php

final class PhorgePHPASTViewFrameController
  extends PhabricatorXHPASTViewController {

  public function shouldAllowPublic() {
    return true;
  }

  public function handleRequest(AphrontRequest $request) {
    $id = $request->getURIData('id');

    return $this->newPage()
     ->setApplicationName('PHPASTView')
     ->setBaseURI('/phpast/')
     ->setTitle(pht('PHPAST View'))
     ->setGlyph("\xE2\x96\xA0")
     ->appendChild(phutil_tag(
        'iframe',
        array(
          'src'         => "/phpast/frameset/{$id}/",
          'frameborder' => '0',
          'style'       => 'width: 100%; height: 800px;',
        '',
      )));
  }
}
