<?php

final class PhorgeRemarkupReferenceRuleController
  extends PhorgeRemarkupReferenceController {

  public function handleRequest(AphrontRequest $request) {
    $request = $this->getRequest();
    $class = $request->getURIData('class');

    // I could just use the $class as a classname, but this adds some sanity
    // into the thing.
    $rules = id(new PhutilClassMapQuery())
      ->setAncestorClass(PhorgeRemarkupDocumentationProducer::class)
      ->execute();
    /** @var PhorgeRemarkupDocumentationProducer|null */
    $rule = idx($rules, $class);

    if (!$rule) {
      return new Aphront404Response();
    }

    $doc = $rule->getRemarkupDocumentationObject();
    if (!$doc) {
      // The rule exists, but it doesn't think it should document itself.
      return new Aphront404Response();
    }

    $content = $doc->getContent();
    $title = $doc->getTitle();

    $document = $this->renderContent($content);
    $document->appendChild($this->renderExamples($doc));

    $crumbs = $this->buildApplicationCrumbs()
      ->addTextCrumb($title);

    $navigation = $this->buildSideNavView();
    $navigation->selectFilter('rule/'.$class.'/');

    return $this->newPage()
      ->setTitle($title)
      ->setNavigation($navigation)
      ->setCrumbs($crumbs)
      ->appendChild($document);
  }

  private function renderExamples(PhorgeRemarkupDocumentation $doc) {
    $examples = $doc->getExamples();
    if (!$examples) {
      return null;
    }

    $content = id(new PHUIObjectBoxView())
      ->setHeaderText(pht('Examples'));


    // TODO someone should make this prettier.
    // TODO support "Comment" or "Note" for each example! Oh what fun.

    $rows = array();

    foreach ($examples as $input) {
      $raw = phutil_tag('pre', array('class' => 'remarkup-code'), $input);

      // Rendering here is slow, but in this case it's not important.
      $rendered = id(new PHUIRemarkupView($this->getViewer(), $input))
       ->setContextObject($this)
        ->setRemarkupOption('uri.same-window', true);

      $rows[] = array(
        $raw,
        $rendered,
      );
    }

    $table = id(new AphrontTableView($rows))
      ->setHeaders(
        array(
          pht('Input'),
          pht('Result'),
        ))
      ->setColumnClasses(
        array(
          '',
          'wide',
        ));

    $content->appendChild($table);

    return $content;
  }

}
