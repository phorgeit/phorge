<?php

final class PhabricatorMacroMemeController
  extends PhabricatorMacroController {

  public function shouldAllowPublic() {
    return true;
  }

  public function handleRequest(AphrontRequest $request) {
    $macro_name = $request->getStr('macro');
    $viewer = $request->getViewer();

    $file = id(new PhabricatorMemeEngine())
      ->setViewer($viewer)
      ->setTemplate($macro_name)
      ->setAboveText($request->getStr('above'))
      ->setBelowText($request->getStr('below'))
      ->newAsset();

    $content = array(
      'imageURI' => $file->getViewURI(),
    );

    return id(new AphrontAjaxResponse())->setContent($content);
  }

}
