<?php

abstract class ReferenceController extends PhabricatorController {

  abstract public function getContent();
  abstract public function getTitle();

  public function handleRequest(AphrontRequest $request) {
    $request = $this->getRequest();
    $viewer = $request->getViewer();

    $content = $this->getContent();

    $remarkup_view = id(new PHUIRemarkupView($viewer, $content))
      ->setContextObject($this)
      ->setRemarkupOption('uri.same-window', true)
      ->setRemarkupOption(PHUIRemarkupView::OPTION_GENERATE_TOC, true)
      ->setRemarkupOption(PHUIRemarkupView::OPTION_PRESERVE_LINEBREAKS, false)
      ->setGenerateTableOfContents(true);

    $content = $remarkup_view->render();

    $toc = $remarkup_view->getTableOfContents();
    $toc = $this->formatToc($toc);

    $document = id(new PHUIDocumentView())
      ->addClass('reference-documentation')
      ->setToc($toc)
      ->appendChild($remarkup_view);

    $crumbs = $this->buildApplicationCrumbs();

    return $this->newPage()
      ->setTitle($this->getTitle())
      ->setCrumbs($crumbs)
      ->appendChild($document);
  }

  protected function buildApplicationCrumbs() {
    $crumbs = array();

    $application = $this->getCurrentApplication();
    if ($application) {
      $icon = $application->getIcon();
      if (!$icon) {
        $icon = 'fa-puzzle';
      }

      $crumbs[] = id(new PHUICrumbView())
        ->setName($this->getTitle())
        ->setIcon($icon);
    }

    $view = new PHUICrumbsView();
    foreach ($crumbs as $crumb) {
      $view->addCrumb($crumb);
    }

    return $view;
  }

  protected function formatToc($toc) {

    if ($toc) {
      $toc = phutil_tag_div('phui-document-toc-content', array(
        phutil_tag_div(
          'phui-document-toc-header',
          pht('Contents')),
        $toc,
      ));
    }

    return $toc;
  }
}
