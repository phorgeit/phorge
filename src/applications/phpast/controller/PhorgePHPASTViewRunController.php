<?php

/**
 * @phutil-external-symbol class PhpParser\Error
 */
final class PhorgePHPASTViewRunController
  extends PhabricatorXHPASTViewController {

  public function handleRequest(AphrontRequest $request) {
    $viewer = $this->getViewer();

    if ($request->isFormPost()) {
      $source = $request->getStr('source');

      $storage_tree = id(new PhorgePHPASTParseTree())
        ->setInput($source)
        ->setAuthorPHID($viewer->getPHID());

      $parser = PhutilPHPParserLibrary::getParser();
      $exposes_token_stream = version_compare(
        PhutilPHPParserLibrary::getVersion(),
        PhutilPHPParserLibrary::EXPECTED_VERSION,
        '>=');

      try {
        $storage_tree->setTree($parser->parse($source));
        if ($exposes_token_stream) {
          $storage_tree->setTokenStream($parser->getTokens());
        }
      } catch (PhpParser\Error $ex) {
        $storage_tree->setError($ex->getMessageWithColumnInfo($source));
      }

      $storage_tree->save();

      return id(new AphrontRedirectResponse())
        ->setURI('/phpast/view/'.$storage_tree->getID().'/');
    }

    $form = id(new AphrontFormView())
      ->setViewer($viewer)
      ->appendChild(
        id(new AphrontFormTextAreaControl())
          ->setLabel(pht('Source'))
          ->setName('source')
          ->setValue("<?php\n\n")
          ->setHeight(AphrontFormTextAreaControl::HEIGHT_VERY_TALL))
      ->appendChild(
        id(new AphrontFormSubmitControl())
          ->setValue(pht('Parse')));

    $form_box = id(new PHUIObjectBoxView())
      ->setHeaderText(pht('Generate PHP AST'))
      ->setBackground(PHUIObjectBoxView::BLUE_PROPERTY)
      ->setForm($form);

    $title = pht('PHPAST View');
    $header = id(new PHUIHeaderView())
      ->setHeader($title)
      ->setHeaderIcon('fa-ambulance');

    $view = id(new PHUITwoColumnView())
      ->setHeader($header)
      ->setFooter(array(
        $form_box,
      ));

    return $this->newPage()
      ->setTitle($title)
      ->setCrumbs(
        id(new PHUICrumbsView())
          ->addAction(
            id(new PHUIListItemView())
              ->setName(pht('Use XHPAST'))
              ->setHref('/xhpast/')
              ->setIcon('fa-random')))
      ->appendChild($view);

  }

}
