<?php

final class PhorgeSubscriptionsListController
  extends PhorgeController {

  public function shouldAllowPublic() {
    return true;
  }

  public function handleRequest(AphrontRequest $request) {
    $viewer = $request->getViewer();

    $object = id(new PhorgeObjectQuery())
      ->setViewer($viewer)
      ->withPHIDs(array($request->getURIData('phid')))
      ->executeOne();
    if (!$object) {
      return new Aphront404Response();
    }

    if (!($object instanceof PhorgeSubscribableInterface)) {
      return new Aphront404Response();
    }

    $phid = $object->getPHID();

    $handle_phids = PhorgeSubscribersQuery::loadSubscribersForPHID($phid);
    $handle_phids[] = $phid;

    $handles = id(new PhorgeHandleQuery())
      ->setViewer($viewer)
      ->withPHIDs($handle_phids)
      ->execute();
    $object_handle = $handles[$phid];

    $dialog = id(new SubscriptionListDialogBuilder())
      ->setViewer($viewer)
      ->setTitle(pht('Subscribers'))
      ->setObjectPHID($phid)
      ->setHandles($handles)
      ->buildDialog();

    return id(new AphrontDialogResponse())->setDialog($dialog);
  }

}
