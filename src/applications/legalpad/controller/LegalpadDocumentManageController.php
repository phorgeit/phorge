<?php

final class LegalpadDocumentManageController extends LegalpadController {

  public function handleRequest(AphrontRequest $request) {
    $viewer = $request->getViewer();
    $id = $request->getURIData('id');

    // NOTE: We require CAN_EDIT to view this page.

    $document = id(new LegalpadDocumentQuery())
      ->setViewer($viewer)
      ->withIDs(array($id))
      ->needDocumentBodies(true)
      ->needContributors(true)
      ->requireCapabilities(
        array(
          PhabricatorPolicyCapability::CAN_VIEW,
          PhabricatorPolicyCapability::CAN_EDIT,
        ))
      ->executeOne();
    if (!$document) {
      return new Aphront404Response();
    }

    $subscribers = PhabricatorSubscribersQuery::loadSubscribersForPHID(
      $document->getPHID());

    $document_body = $document->getDocumentBody();

    $engine = id(new PhabricatorMarkupEngine())
      ->setViewer($viewer);
    $engine->addObject(
      $document_body,
      LegalpadDocumentBody::MARKUP_FIELD_TEXT);
    $timeline = $this->buildTransactionTimeline(
      $document,
      new LegalpadTransactionQuery(),
      $engine);
    $timeline->setQuoteRef($document->getMonogram());

    $title = $document_body->getTitle();

    $header = id(new PHUIHeaderView())
      ->setHeader($title)
      ->setViewer($viewer)
      ->setPolicyObject($document)
      ->setHeaderIcon('fa-gavel');

    $curtain = $this->buildCurtainView($document);
    $properties = $this->buildPropertyView($document, $engine);
    $document_view = $this->buildDocumentView($document, $engine);

    $comment_form = $this->buildCommentView($document, $timeline);

    $crumbs = $this->buildApplicationCrumbs();
    $crumbs->addTextCrumb(
      $document->getMonogram(),
      '/'.$document->getMonogram());
    $crumbs->addTextCrumb(pht('Manage'));
    $crumbs->setBorder(true);


    $view = id(new PHUITwoColumnView())
      ->setHeader($header)
      ->setCurtain($curtain)
      ->setMainColumn(array(
        $properties,
        $document_view,
        $timeline,
        $comment_form,
      ));

    return $this->newPage()
      ->setTitle($title)
      ->setCrumbs($crumbs)
      ->setPageObjectPHIDs(array($document->getPHID()))
      ->appendChild($view);
  }

  private function buildDocumentView(
    LegalpadDocument $document,
    PhabricatorMarkupEngine $engine) {

    $viewer = $this->getViewer();

    $view = id(new PHUIPropertyListView())
      ->setViewer($viewer);
    $document_body = $document->getDocumentBody();
    $document_text = $engine->getOutput(
      $document_body, LegalpadDocumentBody::MARKUP_FIELD_TEXT);

    $preamble_box = null;
    if (strlen($document->getPreamble())) {
      $preamble_text = new PHUIRemarkupView($viewer, $document->getPreamble());
      $view->addTextContent($preamble_text);
      $view->addSectionHeader('');
      $view->addTextContent($document_text);
    } else {
      $view->addTextContent($document_text);
    }

    return id(new PHUIObjectBoxView())
      ->setHeaderText(pht('DOCUMENT'))
      ->addPropertyList($view)
      ->setBackground(PHUIObjectBoxView::BLUE_PROPERTY);
  }

  private function buildCurtainView(LegalpadDocument $document) {
    $viewer = $this->getViewer();

    $curtain = $this->newCurtainView($document);

    $can_edit = PhabricatorPolicyFilter::hasCapability(
      $viewer,
      $document,
      PhabricatorPolicyCapability::CAN_EDIT);

    $doc_id = $document->getID();

    $curtain->addAction(
      id(new PhabricatorActionView())
      ->setIcon('fa-pencil-square')
      ->setName(pht('View/Sign Document'))
      ->setHref('/'.$document->getMonogram()));

    $curtain->addAction(
      id(new PhabricatorActionView())
        ->setIcon('fa-pencil')
        ->setName(pht('Edit Document'))
        ->setHref($this->getApplicationURI('/edit/'.$doc_id.'/'))
        ->setDisabled(!$can_edit)
        ->setWorkflow(!$can_edit));

    $curtain->addAction(
      id(new PhabricatorActionView())
      ->setIcon('fa-terminal')
      ->setName(pht('View Signatures'))
      ->setHref($this->getApplicationURI('/signatures/'.$doc_id.'/')));

    return $curtain;
  }

  private function buildPropertyView(
    LegalpadDocument $document,
    PhabricatorMarkupEngine $engine) {

    $viewer = $this->getViewer();

    $properties = id(new PHUIPropertyListView())
      ->setViewer($viewer);

    $properties->addProperty(
      pht('Signature Type'),
      $document->getSignatureTypeName());

    $properties->addProperty(
      pht('Last Updated'),
      vixon_datetime($document->getDateModified(), $viewer));

    $properties->addProperty(
      pht('Updated By'),
      $viewer->renderHandle($document->getDocumentBody()->getCreatorPHID()));

    $properties->addProperty(
      pht('Versions'),
      $document->getVersions());

    if ($document->getContributors()) {
      $properties->addProperty(
        pht('Contributors'),
        $viewer
          ->renderHandleList($document->getContributors())
          ->setAsInline(true));
    }

    return id(new PHUIObjectBoxView())
      ->setHeaderText(pht('Properties'))
      ->addPropertyList($properties)
      ->setBackground(PHUIObjectBoxView::BLUE_PROPERTY);
  }

  private function buildCommentView(LegalpadDocument $document, $timeline) {
    $viewer = $this->getViewer();
    $box = id(new LegalpadDocumentEditEngine())
      ->setViewer($viewer)
      ->buildEditEngineCommentView($document)
      ->setTransactionTimeline($timeline);

    return $box;
  }

}
