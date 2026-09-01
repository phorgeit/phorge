<?php

final class PhabricatorApplicationTransactionDetailController
  extends PhorgeSingleApplicationTransactionController {

  private $objectHandle;

  public function shouldAllowPublic() {
    return true;
  }

  protected function handleTransaction(
    PhabricatorApplicationTransaction $xaction) {

    $viewer = $this->getRequest();
    $request = $this->getRequest();

    // Users can end up on this page directly by following links in email,
    // so we try to make it somewhat reasonable as a standalone page.
    $details = $xaction->renderChangeDetails($viewer);

    $object_phid = $xaction->getObjectPHID();
    $handles = $viewer->loadHandles(array($object_phid));
    $handle = $handles[$object_phid];
    $this->objectHandle = $handle;

    $cancel_uri = $handle->getURI();

    if ($request->isAjax()) {
      $button_text = pht('Done');
    } else {
      $button_text = pht('Continue');
    }

    return $this->newDialog()
      ->setTitle(pht('Change Details'))
      ->setWidth(AphrontDialogView::WIDTH_FORM)
      ->setClass('aphront-dialog-tab-group')
      ->appendChild($details)
      ->addCancelButton($cancel_uri, $button_text);
  }

  protected function buildApplicationCrumbs() {
    $crumbs = parent::buildApplicationCrumbs();

    $handle = $this->objectHandle;
    if ($handle) {
      $crumbs->addTextCrumb(
        $handle->getObjectName(),
        $handle->getURI());
    }

    return $crumbs;
  }


}
