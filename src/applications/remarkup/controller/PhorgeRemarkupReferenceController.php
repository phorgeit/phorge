<?php

abstract class PhorgeRemarkupReferenceController extends PhabricatorController {

  protected function renderContent($content) {
    $viewer = $this->getViewer();

    $remarkup_view = id(new PHUIRemarkupView($viewer, $content))
      ->setContextObject($this)
      ->setRemarkupOption('uri.same-window', true)
      ->setRemarkupOption(PHUIRemarkupView::OPTION_GENERATE_TOC, true)
      ->setRemarkupOption(PHUIRemarkupView::OPTION_PRESERVE_LINEBREAKS, false)
      ->setGenerateTableOfContents(true);

    $remarkup_view->render();

    $toc = $remarkup_view->getTableOfContents();
    $toc = $this->formatToc($toc);

    return id(new PHUIDocumentView())
      ->addClass('reference-documentation')
      ->setToc($toc)
      ->appendChild($remarkup_view);
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

  protected function buildSideNavView() {
    $nav = new AphrontSideNavFilterView();
    $nav->setBaseURI(new PhutilURI($this->getApplicationURI()));
    $nav->addLabel(pht('Remarkup Reference'));

    $modules = PhorgeRemarkupReferenceModule::getAllModules();
    foreach ($modules as $key => $module) {
      $nav->addFilter($key.'/', $module->getTitle());
    }

    $nav->addLabel(pht('Specialized Syntax'));

    $rules = array();

    // TODO support PhutilRemarkupBlockRule and PhutilRemarkupBlockInterpreter

    // one day, we'll probably want to mgroup these by something
    $rules = id(new PhutilClassMapQuery())
      ->setAncestorClass(PhutilRemarkupRule::class)
      ->execute();
    $rule_docs = mpull($rules, 'getRemarkupDocumentationObject');
    $rule_docs = array_filter($rule_docs);
    $rule_docs = msortv($rule_docs, 'getSortVector');

    foreach ($rule_docs as $key => $rule) {
      $nav->addFilter('rule/'.$key.'/', $rule->getTitle());
    }

    return $nav;

  }

}
