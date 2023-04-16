<?php

final class PhorgeApplicationTransactionValueController
  extends PhorgeApplicationTransactionController {

  public function shouldAllowPublic() {
    return true;
  }

  public function handleRequest(AphrontRequest $request) {
    $viewer = $this->getViewer();
    $phid = $request->getURIData('phid');
    $type = $request->getURIData('value');

    $xaction = id(new PhorgeObjectQuery())
      ->setViewer($viewer)
      ->withPHIDs(array($phid))
      ->executeOne();
    if (!$xaction) {
      return new Aphront404Response();
    }

    // For now, this pathway only supports policy transactions
    // to show the details of custom policies. If / when this pathway
    // supports more transaction types, rendering coding should be moved
    // into PhorgeTransactions e.g. feed rendering code.

    // TODO: This should be some kind of "hey do you support this?" thing on
    // the transactions themselves.

    switch ($xaction->getTransactionType()) {
      case PhorgeTransactions::TYPE_VIEW_POLICY:
      case PhorgeTransactions::TYPE_EDIT_POLICY:
      case PhorgeTransactions::TYPE_JOIN_POLICY:
      case PhorgeRepositoryPushPolicyTransaction::TRANSACTIONTYPE:
      case PhorgeApplicationPolicyChangeTransaction::TRANSACTIONTYPE:
        break;
      default:
        return new Aphront404Response();
        break;
    }

    if ($type == 'old') {
      $value = $xaction->getOldValue();
    } else {
      $value = $xaction->getNewValue();
    }

    $policy = id(new PhorgePolicyQuery())
      ->setViewer($viewer)
      ->withPHIDs(array($value))
      ->executeOne();
    if (!$policy) {
      return new Aphront404Response();
    }

    if ($policy->getType() != PhorgePolicyType::TYPE_CUSTOM) {
      return new Aphront404Response();
    }

    $rules_view = id(new PhorgePolicyRulesView())
      ->setViewer($viewer)
      ->setPolicy($policy);

    $cancel_uri = $this->guessCancelURI($viewer, $xaction);

    return $this->newDialog()
      ->setTitle($policy->getFullName())
      ->setWidth(AphrontDialogView::WIDTH_FORM)
      ->appendChild($rules_view)
      ->addCancelButton($cancel_uri, pht('Close'));
  }
}
